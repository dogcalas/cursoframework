<?php
/*
 *  $Id: Query.php 1393 2007-05-19 17:49:16Z zYne $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Doctrine_Query_Abstract
 *
 * @package     Doctrine
 * @subpackage  Query
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision: 1393 $
 * @author      Konsta Vesterinen <kvesteri@cc.hut.fi>
 * @todo        See {@link Doctrine_Query}
 */
abstract class Doctrine_Query_Abstract
{
    /**
     * QUERY TYPE CONSTANTS
     */
  
    /**
     * constant for SELECT queries
     */
    const SELECT = 0;

    /**
     * constant for DELETE queries
     */
    const DELETE = 1;

    /**
     * constant for UPDATE queries
     */
    const UPDATE = 2;

    /**
     * constant for INSERT queries
     */
    const INSERT = 3;

    /**
     * constant for CREATE queries
     */
    const CREATE = 4;

    /** @todo document the query states (and the transitions between them). */
    /**
     * A query object is in CLEAN state when it has NO unparsed/unprocessed DQL parts.
     */
    const STATE_CLEAN  = 1;

    /**
     * A query object is in state DIRTY when it has DQL parts that have not yet been
     * parsed/processed.
     */
    const STATE_DIRTY  = 2;

    /**
     * A query is in DIRECT state when ... ?
     */
    const STATE_DIRECT = 3;

    /**
     * A query object is on LOCKED state when ... ?
     */
    const STATE_LOCKED = 4;

    /**
     * @var array  Table alias map. Keys are SQL aliases and values DQL aliases.
     */
    protected $_tableAliasMap = array();

    /**
     * @var Doctrine_View  The view object used by this query, if any.
     */
    protected $_view;

    /**
     * @var integer $_state   The current state of this query.
     */
    protected $_state = Doctrine_Query::STATE_CLEAN;

    /**
     * @var array $params  The parameters of this query.
     */
    protected $_params = array('join' => array(),
                               'where' => array(),
                               'set' => array(),
                               'having' => array());

    /* Caching properties */
    /**
     * @var Doctrine_Cache_Interface  The cache driver used for caching result sets.
     */
    protected $_resultCache;
    /**
     * @var boolean $_expireResultCache  A boolean value that indicates whether or not
     *                                   expire the result cache.
     */
    protected $_expireResultCache = false;
    protected $_resultCacheTTL;

    /**
     * @var Doctrine_Cache_Interface  The cache driver used for caching queries.
     */
    protected $_queryCache;
    protected $_expireQueryCache = false;
    protected $_queryCacheTTL;


    /**
     * @var Doctrine_Connection  The connection used by this query object.
     */
    protected $_conn;


    /**
     * @var array $_sqlParts  The SQL query string parts. Filled during the DQL parsing process.
     */
    protected $_sqlParts = array(
            'select'    => array(),
            'distinct'  => false,
            'forUpdate' => false,
            'from'      => array(),
            'set'       => array(),
            'join'      => array(),
            'where'     => array(),
            'groupby'   => array(),
            'having'    => array(),
            'orderby'   => array(),
            'limit'     => false,
            'offset'    => false,
            );

    /**
     * @var array $_dqlParts                an array containing all DQL query parts
     */
    protected $_dqlParts = array(
                            'from'      => array(),
                            'select'    => array(),
                            'forUpdate' => false,
                            'set'       => array(),
                            'join'      => array(),
                            'where'     => array(),
                            'groupby'   => array(),
                            'having'    => array(),
                            'orderby'   => array(),
                            'limit'     => array(),
                            'offset'    => array(),
                            );


    /**
     * @var array $_queryComponents   Two dimensional array containing the components of this query,
     *                                informations about their relations and other related information.
     *                                The components are constructed during query parsing.
     *
     *      Keys are component aliases and values the following:
     *
     *          table               table object associated with given alias
     *
     *          relation            the relation object owned by the parent
     *
     *          parent              the alias of the parent
     *
     *          agg                 the aggregates of this component
     *
     *          map                 the name of the column / aggregate value this
     *                              component is mapped to a collection
     */
    protected $_queryComponents = array();

    /**
     * @var integer $type                   the query type
     *
     * @see Doctrine_Query::* constants
     */
    protected $_type = self::SELECT;

    /**
     * @var Doctrine_Hydrator   The hydrator object used to hydrate query results.
     */
    protected $_hydrator;

    /**
     * @var Doctrine_Query_Tokenizer  The tokenizer that is used during the query parsing process.
     */
    protected $_tokenizer;

    /**
     * @var Doctrine_Query_Parser  The parser that is used for query parsing.
     */
    protected $_parser;

    /**
     * @var array $_tableAliasSeeds         A simple array keys representing table aliases and values
     *                                      table alias seeds. The seeds are used for generating short table
     *                                      aliases.
     */
    protected $_tableAliasSeeds = array();

    /**
     * @var array $_options                 an array of options
     */
    protected $_options    = array(
                            'fetchMode'      => Doctrine::FETCH_RECORD
                            );

    /**
     * @var array $_enumParams              an array containing the keys of the parameters that should be enumerated
     */
    protected $_enumParams = array();

    /**
     * @var boolean
     */
    protected $_isLimitSubqueryUsed = false;

    protected $_pendingSetParams = array();

    /**
     * @var array components used in the DQL statement
     */
    protected $_components;

    /**
     * @var bool Boolean variable for whether or not the preQuery process has been executed
     */
    protected $_preQueried = false;
     /**
     * @var bool Boolean variable para saber si hay reglas de mongo al eliminar
     */
    protected $_reglaEliminar = false;

    /**
     * Constructor.
     *
     * @param Doctrine_Connection  The connection object the query will use.
     * @param Doctrine_Hydrator_Abstract  The hydrator that will be used for generating result sets.
     */
    public function __construct(Doctrine_Connection $connection = null,
            Doctrine_Hydrator_Abstract $hydrator = null)
    {
        if ($connection === null) {
            $connection = Doctrine_Manager::getInstance()->getCurrentConnection();
        }
        if ($hydrator === null) {
            $hydrator = new Doctrine_Hydrator();
        }
        $this->_conn = $connection;
        $this->_hydrator = $hydrator;
        $this->_tokenizer = new Doctrine_Query_Tokenizer();
        $this->_resultCacheTTL = $this->_conn->getAttribute(Doctrine::ATTR_RESULT_CACHE_LIFESPAN);
        $this->_queryCacheTTL = $this->_conn->getAttribute(Doctrine::ATTR_QUERY_CACHE_LIFESPAN);
    }

    /**
     * setOption
     *
     * @param string $name      option name
     * @param string $value     option value
     * @return Doctrine_Query   this object
     */
    public function setOption($name, $value)
    {
        if ( ! isset($this->_options[$name])) {
            throw new Doctrine_Query_Exception('Unknown option ' . $name);
        }
        $this->_options[$name] = $value;
    }

    /**
     * hasTableAlias
     * whether or not this object has given tableAlias
     *
     * @param string $tableAlias    the table alias to be checked
     * @return boolean              true if this object has given alias, otherwise false
     * @deprecated
     */
    public function hasTableAlias($sqlTableAlias)
    {
        return $this->hasSqlTableAlias($sqlTableAlias);
    }

    /**
     * hasSqlTableAlias
     * whether or not this object has given tableAlias
     *
     * @param string $tableAlias    the table alias to be checked
     * @return boolean              true if this object has given alias, otherwise false
     */
    public function hasSqlTableAlias($sqlTableAlias)
    {
        return (isset($this->_tableAliasMap[$sqlTableAlias]));
    }

    /**
     * getTableAliases
     * returns all table aliases
     *
     * @return array        table aliases as an array
     * @deprecated
     */
    public function getTableAliases()
    {
        return $this->getTableAliasMap();
    }

    /**
     * getTableAliasMap
     * returns all table aliases
     *
     * @return array        table aliases as an array
     */
    public function getTableAliasMap()
    {
        return $this->_tableAliasMap;
    }

    /**
     * getQueryPart
     * gets a query part from the query part array
     *
     * @param string $name          the name of the query part to be set
     * @param string $part          query part string
     * @throws Doctrine_Query_Exception   if trying to set unknown query part
     * @return Doctrine_Query_Abstract  this object
     * @deprecated
     */
    public function getQueryPart($part)
    {
        return $this->getSqlQueryPart($part);
    }

    /**
     * getSqlQueryPart
     * gets an SQL query part from the SQL query part array
     *
     * @param string $name          the name of the query part to be set
     * @param string $part          query part string
     * @throws Doctrine_Query_Exception   if trying to set unknown query part
     * @return Doctrine_Hydrate     this object
     */
    public function getSqlQueryPart($part)
    {
        if ( ! isset($this->_sqlParts[$part])) {
            throw new Doctrine_Query_Exception('Unknown SQL query part ' . $part);
        }
        return $this->_sqlParts[$part];
    }

    /**
     * setQueryPart
     * sets a query part in the query part array
     *
     * @param string $name          the name of the query part to be set
     * @param string $part          query part string
     * @throws Doctrine_Query_Exception   if trying to set unknown query part
     * @return Doctrine_Hydrate     this object
     * @deprecated
     */
    public function setQueryPart($name, $part)
    {
        return $this->setSqlQueryPart($name, $part);
    }

    /**
     * setSqlQueryPart
     * sets an SQL query part in the SQL query part array
     *
     * @param string $name          the name of the query part to be set
     * @param string $part          query part string
     * @throws Doctrine_Query_Exception   if trying to set unknown query part
     * @return Doctrine_Hydrate     this object
     */
    public function setSqlQueryPart($name, $part)
    {
        if ( ! isset($this->_sqlParts[$name])) {
            throw new Doctrine_Query_Exception('Unknown query part ' . $name);
        }

        if ($name !== 'limit' && $name !== 'offset') {
            if (is_array($part)) {
                $this->_sqlParts[$name] = $part;
            } else {
                $this->_sqlParts[$name] = array($part);
            }
        } else {
            $this->_sqlParts[$name] = $part;
        }

        return $this;
    }

    /**
     * addQueryPart
     * adds a query part in the query part array
     *
     * @param string $name          the name of the query part to be added
     * @param string $part          query part string
     * @throws Doctrine_Query_Exception   if trying to add unknown query part
     * @return Doctrine_Hydrate     this object
     * @deprecated
     */
    public function addQueryPart($name, $part)
    {
        return $this->addSqlQueryPart($name, $part);
    }

    /**
     * addSqlQueryPart
     * adds an SQL query part to the SQL query part array
     *
     * @param string $name          the name of the query part to be added
     * @param string $part          query part string
     * @throws Doctrine_Query_Exception   if trying to add unknown query part
     * @return Doctrine_Hydrate     this object
     */
    public function addSqlQueryPart($name, $part)
    {
        if ( ! isset($this->_sqlParts[$name])) {
            throw new Doctrine_Query_Exception('Unknown query part ' . $name);
        }
        if (is_array($part)) {
            $this->_sqlParts[$name] = array_merge($this->_sqlParts[$name], $part);
        } else {
            $this->_sqlParts[$name][] = $part;
        }
        return $this;
    }

    /**
     * removeQueryPart
     * removes a query part from the query part array
     *
     * @param string $name          the name of the query part to be removed
     * @throws Doctrine_Query_Exception   if trying to remove unknown query part
     * @return Doctrine_Hydrate     this object
     * @deprecated
     */
    public function removeQueryPart($name)
    {
        return $this->removeSqlQueryPart($name);
    }

    /**
     * removeSqlQueryPart
     * removes a query part from the query part array
     *
     * @param string $name          the name of the query part to be removed
     * @throws Doctrine_Query_Exception   if trying to remove unknown query part
     * @return Doctrine_Hydrate     this object
     */
    public function removeSqlQueryPart($name)
    {
        if ( ! isset($this->_sqlParts[$name])) {
            throw new Doctrine_Query_Exception('Unknown query part ' . $name);
        }

        if ($name == 'limit' || $name == 'offset') {
            $this->_sqlParts[$name] = false;
        } else {
            $this->_sqlParts[$name] = array();
        }

        return $this;
    }

    /**
     * removeDqlQueryPart
     * removes a dql query part from the dql query part array
     *
     * @param string $name          the name of the query part to be removed
     * @throws Doctrine_Query_Exception   if trying to remove unknown query part
     * @return Doctrine_Hydrate     this object
     */
    public function removeDqlQueryPart($name)
    {
        if ( ! isset($this->_dqlParts[$name])) {
            throw new Doctrine_Query_Exception('Unknown query part ' . $name);
        }

        if ($name == 'limit' || $name == 'offset') {
            $this->_dqlParts[$name] = false;
        } else {
            $this->_dqlParts[$name] = array();
        }

        return $this;
    }

    /**
     * getParams
     *
     * @return array
     */
    public function getParams($params = array())
    {
        return array_merge($this->_params['join'], $this->_params['set'], $this->_params['where'], $this->_params['having'], $params);
    }

    /**
     * setParams
     *
     * @param array $params
     */
    public function setParams(array $params = array()) {
        $this->_params = $params;
    }

    /**
     * setView
     * sets a database view this query object uses
     * this method should only be called internally by doctrine
     *
     * @param Doctrine_View $view       database view
     * @return void
     */
    public function setView(Doctrine_View $view)
    {
        $this->_view = $view;
    }

    /**
     * getView
     * returns the view associated with this query object (if any)
     *
     * @return Doctrine_View        the view associated with this query object
     */
    public function getView()
    {
        return $this->_view;
    }

    /**
     * limitSubqueryUsed
     *
     * @return boolean
     */
    public function isLimitSubqueryUsed()
    {
        return $this->_isLimitSubqueryUsed;
    }

    /**
     * convertEnums
     * convert enum parameters to their integer equivalents
     *
     * @return array    converted parameter array
     */
    public function convertEnums($params)
    {
        $table = $this->getRoot();

        // $position tracks the position of the parameter, to ensure we're converting
        // the right parameter value when simple ? placeholders are used.
        // This only works because SET is only allowed in update statements and it's
        // the first place where parameters can occur.. see issue #935
        $position = 0;
        foreach ($this->_pendingSetParams as $fieldName => $value) {
            $e = explode('.', $fieldName);
            $fieldName = isset($e[1]) ? $e[1]:$e[0];
            if ($table->getTypeOf($fieldName) == 'enum') {
                $value = $value === '?' ? $position : $value;
                $this->addEnumParam($value, $table, $fieldName);
            }
            ++$position;
        }
        $this->_pendingSetParams = array();

        foreach ($this->_enumParams as $key => $values) {
            if (isset($params[$key])) {
                if ( ! empty($values)) {
                    $params[$key] = $values[0]->enumIndex($values[1], $params[$key]);
                }
            }
        }

        return $params;
    }

    /**
     * applyInheritance
     * applies column aggregation inheritance to DQL / SQL query
     *
     * @return string
     */
    public function applyInheritance()
    {
        // get the inheritance maps
        $array = array();

        foreach ($this->_queryComponents as $componentAlias => $data) {
            $tableAlias = $this->getSqlTableAlias($componentAlias);
            $array[$tableAlias][] = $data['table']->inheritanceMap;
        }

        // apply inheritance maps
        $str = '';
        $c = array();

        $index = 0;
        foreach ($array as $tableAlias => $maps) {
            $a = array();

            // don't use table aliases if the query isn't a select query
            if ($this->_type !== Doctrine_Query::SELECT) {
                $tableAlias = '';
            } else {
                $tableAlias .= '.';
            }

            foreach ($maps as $map) {
                $b = array();
                foreach ($map as $field => $value) {
                    $identifier = $this->_conn->quoteIdentifier($tableAlias . $field);

                    if ($index > 0) {
                        $b[] = '(' . $identifier . ' = ' . $this->_conn->quote($value)
                             . ' OR ' . $identifier . ' IS NULL)';
                    } else {
                        $b[] = $identifier . ' = ' . $this->_conn->quote($value);
                    }
                }

                if ( ! empty($b)) {
                    $a[] = implode(' AND ', $b);
                }
            }

            if ( ! empty($a)) {
                $c[] = implode(' AND ', $a);
            }
            $index++;
        }

        $str .= implode(' AND ', $c);

        return $str;
    }

    /**
     * getTableAlias
     * some database such as Oracle need the identifier lengths to be < ~30 chars
     * hence Doctrine creates as short identifier aliases as possible
     *
     * this method is used for the creation of short table aliases, its also
     * smart enough to check if an alias already exists for given component (componentAlias)
     *
     * @param string $componentAlias    the alias for the query component to search table alias for
     * @param string $tableName         the table name from which the table alias is being created
     * @return string                   the generated / fetched short alias
     * @deprecated
     */
    public function getTableAlias($componentAlias, $tableName = null)
    {
        return $this->getSqlTableAlias($componentAlias, $tableName);
    }

    /**
     * getSqlTableAlias
     * some database such as Oracle need the identifier lengths to be < ~30 chars
     * hence Doctrine creates as short identifier aliases as possible
     *
     * this method is used for the creation of short table aliases, its also
     * smart enough to check if an alias already exists for given component (componentAlias)
     *
     * @param string $componentAlias    the alias for the query component to search table alias for
     * @param string $tableName         the table name from which the table alias is being created
     * @return string                   the generated / fetched short alias
     */
    public function getSqlTableAlias($componentAlias, $tableName = null)
    {
        $alias = array_search($componentAlias, $this->_tableAliasMap);

        if ($alias !== false) {
            return $alias;
        }

        if ($tableName === null) {
            throw new Doctrine_Query_Exception("Couldn't get short alias for " . $componentAlias);
        }

        return $this->generateTableAlias($componentAlias, $tableName);
    }

    /**
     * generateNewTableAlias
     * generates a new alias from given table alias
     *
     * @param string $tableAlias    table alias from which to generate the new alias from
     * @return string               the created table alias
     * @deprecated
     */
    public function generateNewTableAlias($oldAlias)
    {
        return $this->generateNewSqlTableAlias($oldAlias);
    }

    /**
     * generateNewSqlTableAlias
     * generates a new alias from given table alias
     *
     * @param string $tableAlias    table alias from which to generate the new alias from
     * @return string               the created table alias
     */
    public function generateNewSqlTableAlias($oldAlias)
    {
        if (isset($this->_tableAliasMap[$oldAlias])) {
            // generate a new alias
            $name = substr($oldAlias, 0, 1);
            $i    = ((int) substr($oldAlias, 1));

            if ($i == 0) {
                $i = 1;
            }

            $newIndex  = ($this->_tableAliasSeeds[$name] + $i);

            return $name . $newIndex;
        }

        return $oldAlias;
    }

    /**
     * getTableAliasSeed
     * returns the alias seed for given table alias
     *
     * @param string $tableAlias    table alias that identifies the alias seed
     * @return integer              table alias seed
     * @deprecated
     */
    public function getTableAliasSeed($sqlTableAlias)
    {
        return $this->getSqlTableAliasSeed($sqlTableAlias);
    }

    /**
     * getSqlTableAliasSeed
     * returns the alias seed for given table alias
     *
     * @param string $tableAlias    table alias that identifies the alias seed
     * @return integer              table alias seed
     */
    public function getSqlTableAliasSeed($sqlTableAlias)
    {
        if ( ! isset($this->_tableAliasSeeds[$sqlTableAlias])) {
            return 0;
        }
        return $this->_tableAliasSeeds[$sqlTableAlias];
    }

    /**
     * hasAliasDeclaration
     * whether or not this object has a declaration for given component alias
     *
     * @param string $componentAlias    the component alias the retrieve the declaration from
     * @return boolean
     */
    public function hasAliasDeclaration($componentAlias)
    {
        return isset($this->_queryComponents[$componentAlias]);
    }

    /**
     * getAliasDeclaration
     * get the declaration for given component alias
     *
     * @param string $componentAlias    the component alias the retrieve the declaration from
     * @return array                    the alias declaration
     * @deprecated
     */
    public function getAliasDeclaration($componentAlias)
    {
        return $this->getQueryComponent($componentAlias);
    }

    /**
     * getQueryComponent
     * get the declaration for given component alias
     *
     * @param string $componentAlias    the component alias the retrieve the declaration from
     * @return array                    the alias declaration
     */
    public function getQueryComponent($componentAlias)
    {
        if ( ! isset($this->_queryComponents[$componentAlias])) {
            throw new Doctrine_Query_Exception('Unknown component alias ' . $componentAlias);
        }

        return $this->_queryComponents[$componentAlias];
    }

    /**
     * copyAliases
     * copy aliases from another Hydrate object
     *
     * this method is needed by DQL subqueries which need the aliases
     * of the parent query
     *
     * @param Doctrine_Hydrate $query   the query object from which the
     *                                  aliases are copied from
     * @return Doctrine_Hydrate         this object
     */
    public function copyAliases(Doctrine_Query_Abstract $query)
    {
        $this->_tableAliasMap = $query->_tableAliasMap;
        $this->_queryComponents     = $query->_queryComponents;
        $this->_tableAliasSeeds = $query->_tableAliasSeeds;
        return $this;
    }

    /**
     * getRootAlias
     * returns the alias of the the root component
     *
     * @return array
     */
    public function getRootAlias()
    {
        if ( ! $this->_queryComponents) {
          $this->getSql();
        }
        reset($this->_queryComponents);

        return key($this->_queryComponents);
    }

    /**
     * getRootDeclaration
     * returns the root declaration
     *
     * @return array
     */
    public function getRootDeclaration()
    {
        $map = reset($this->_queryComponents);
        return $map;
    }

    /**
     * getRoot
     * returns the root component for this object
     *
     * @return Doctrine_Table       root components table
     */
    public function getRoot()
    {
        $map = reset($this->_queryComponents);

        if ( ! isset($map['table'])) {
            throw new Doctrine_Query_Exception('Root component not initialized.');
        }

        return $map['table'];
    }

    /**
     * generateTableAlias
     * generates a table alias from given table name and associates
     * it with given component alias
     *
     * @param string $componentAlias    the component alias to be associated with generated table alias
     * @param string $tableName         the table name from which to generate the table alias
     * @return string                   the generated table alias
     * @deprecated
     */
    public function generateTableAlias($componentAlias, $tableName)
    {
        return $this->generateSqlTableAlias($componentAlias, $tableName);
    }

    /**
     * generateSqlTableAlias
     * generates a table alias from given table name and associates
     * it with given component alias
     *
     * @param string $componentAlias    the component alias to be associated with generated table alias
     * @param string $tableName         the table name from which to generate the table alias
     * @return string                   the generated table alias
     */
    public function generateSqlTableAlias($componentAlias, $tableName)
    {
        preg_match('/([^_])/', $tableName, $matches);
        $char = strtolower($matches[0]);

        $alias = $char;

        if ( ! isset($this->_tableAliasSeeds[$alias])) {
            $this->_tableAliasSeeds[$alias] = 1;
        }

        while (isset($this->_tableAliasMap[$alias])) {
            if ( ! isset($this->_tableAliasSeeds[$alias])) {
                $this->_tableAliasSeeds[$alias] = 1;
            }
            $alias = $char . ++$this->_tableAliasSeeds[$alias];
        }

        $this->_tableAliasMap[$alias] = $componentAlias;

        return $alias;
    }

    /**
     * getComponentAlias
     * get component alias associated with given table alias
     *
     * @param string $sqlTableAlias    the SQL table alias that identifies the component alias
     * @return string               component alias
     */
    public function getComponentAlias($sqlTableAlias)
    {
        if ( ! isset($this->_tableAliasMap[$sqlTableAlias])) {
            throw new Doctrine_Query_Exception('Unknown table alias ' . $sqlTableAlias);
        }
        return $this->_tableAliasMap[$sqlTableAlias];
    }

    /**
     * _execute
     *
     * @param array $params
     * @return PDOStatement  The executed PDOStatement.
     */
    protected function _execute($params)
    {
        $params = $this->_conn->convertBooleans($params);

        if ( ! $this->_view) {
            if ($this->_queryCache || $this->_conn->getAttribute(Doctrine::ATTR_QUERY_CACHE)) {
                $queryCacheDriver = $this->getQueryCacheDriver();
                // calculate hash for dql query
                $dql = $this->getDql();
                $hash = md5($dql . 'DOCTRINE_QUERY_CACHE_SALT');
                $cached = $queryCacheDriver->fetch($hash);
                if ($cached) {
                    $query = $this->_constructQueryFromCache($cached);
                } else {
                    $query = $this->getSqlQuery($params);
                    $serializedQuery = $this->getCachedForm($query);
                    $queryCacheDriver->save($hash, $serializedQuery, $this->getQueryCacheLifeSpan());
                }
            } else {
                $query = $this->getSqlQuery($params);
            }
            $params = $this->convertEnums($params);
        } else {
            $query = $this->_view->getSelectSql();
        }

        if ($this->isLimitSubqueryUsed() &&
                $this->_conn->getAttribute(Doctrine::ATTR_DRIVER_NAME) !== 'mysql') {
            $params = array_merge($params, $params);
        }

        if ($this->_type !== self::SELECT) {
            return $this->_conn->exec($query, $params);
        }

        $stmt = $this->_conn->execute($query, $params);
        return $stmt;
    }

    /**
     * execute
     * executes the query and populates the data set
     *
     * @param array $params
     * @return Doctrine_Collection            the root collection
     */
    public function execute($params = array(), $hydrationMode = null)
    {
        $this->_preQuery();

        if ($hydrationMode !== null) {
            $this->_hydrator->setHydrationMode($hydrationMode);
        }

        $params = $this->getParams($params);

        if ($this->_resultCache && $this->_type == self::SELECT) {
            $cacheDriver = $this->getResultCacheDriver();

            $dql = $this->getDql();
            // calculate hash for dql query
            $hash = md5($dql . var_export($params, true));

            $cached = ($this->_expireResultCache) ? false : $cacheDriver->fetch($hash);

            if ($cached === false) {
                // cache miss
                $stmt = $this->_execute($params);
                $this->_hydrator->setQueryComponents($this->_queryComponents);
                $result = $this->_hydrator->hydrateResultSet($stmt, $this->_tableAliasMap);

                $cached = $this->getCachedForm($result);
                $cacheDriver->save($hash, $cached, $this->getResultCacheLifeSpan());
            } else {
                $result = $this->_constructQueryFromCache($cached);
            }
        } else {
            $stmt = $this->_execute($params);

            if (is_integer($stmt)) {
                $result = $stmt;
            } else {
                $this->_hydrator->setQueryComponents($this->_queryComponents);
                $result = $this->_hydrator->hydrateResultSet($stmt, $this->_tableAliasMap);
            }
        }
        if ($this->_type == self::DELETE&&$this->_reglaEliminar == true ) {
            
            if($result==0)
                throw new ZendExt_Exception('ECR01');
        } 
        return $result;
    }


    /**
     * Get the dql call back for this query
     *
     * @return array $callback
     */
    protected function _getDqlCallback()
    {
        $callback = false;
        if ( ! empty($this->_dqlParts['from'])) {
            switch ($this->_type) {
                case self::DELETE:
                    $callback = array(
                        'callback' => 'preDqlDelete',
                        'const' => Doctrine_Event::RECORD_DQL_DELETE
                    );
                break;
                case self::UPDATE:
                    $callback = array(
                        'callback' => 'preDqlUpdate',
                        'const' => Doctrine_Event::RECORD_DQL_UPDATE
                    );
                break;
                case self::SELECT:
                    $callback = array(
                        'callback' => 'preDqlSelect',
                        'const' => Doctrine_Event::RECORD_DQL_SELECT
                    );
                break;
            }
        }

        return $callback;
    }

    /**
     * Pre query method which invokes the pre*Query() methods on the model instance or any attached
     * record listeners
     *
     * @return void
     */
    protected function _preQuery()
    {
        if ($this->_preQueried) {
            return;
        }

        $this->_preQueried = true;

        if (Doctrine_Manager::getInstance()->getAttribute('use_dql_callbacks')) {
            $callback = $this->_getDqlCallback();

            // if there is no callback for the query type, then we can return early
            if ( ! $callback) {
                return;
            }

            $copy = $this->copy();
            $copy->getSqlQuery();

            foreach ($copy->getQueryComponents() as $alias => $component) {
                $table = $component['table'];
                $record = $table->getRecordInstance();

                // check (and call) preDql*() callback on the model class
                if (method_exists($record, $callback['callback'])) {
                    $record->$callback['callback']($this, $component, $alias);
                }

                // trigger preDql*() callback event
                $params = array('component`' => $component, 'alias' => $alias);
                $event = new Doctrine_Event($record, $callback['const'], $this, $params);
                $table->getRecordListener()->$callback['callback']($event);
            }
        }

        // Invoke preQuery() hook on Doctrine_Query for child classes which implement this hook
        $this->preQuery();
    }

    /**
     * Blank hook methods which can be implemented in Doctrine_Query child classes
     *
     * @return void
     */
    public function preQuery()
    {        
        if(isset($_SESSION["UCID_Cedrux_UCI"])){
        $session = Zend_Registry::get('session');         
        if($session->idestructura){           
        if ($this->_type == self::DELETE) {
            if($this->LongitudFrom()==1){  
            $ta = Doctrine::getTable($this->getRootAlias());            
            $tabla = $ta->getTableName(); //seg_usuario,seg_rol
            $alias = $this->getRootAlias(); //Seg_Usuario,SegRol
            
            $reglas=$this->ObtenerReglasEliminar();
            if (!empty($reglas)) {
                
                foreach($reglas as $re){ 
                
                $this->addWhere($alias.'.'.$re['campo'].$re['operador'].' ?', $re['valor']);
                   }
                  $this->_reglaEliminar =true;
                   
            }
            }else if($this->LongitudFrom()==2){
                $from=$this->FromPartes();
                $ta = Doctrine::getTable($from[0]);
                $tabla = $ta->getTableName(); //seg_usuario,seg_rol
                
                $reglas=$this->ObtenerReglasEliminar();
                 if (!empty($reglas)) {    
                     
                   foreach($reglas as $re){                   
                $this->addWhere($from[1].'.'.$re['campo'].$re['operador'].' ?', $re['valor']);
                   }                

                   $this->_reglaEliminar =true;
                   
            }
            }
        }
            
       if ($this->_type == self::SELECT) {
        //---------------Aqui-------------------------
            
             $xml = ZendExt_FastResponse::getXML('mongo');
        $recursos = array();
        foreach ($xml->children() as $recu) {           
            $instalado = (string)$recu['instalado'];            
        }
       if($instalado == 1)
            
            $this->FiltroSelect();
            
        } 
    }    
   }        
  }


    /**
     * Constructs the query from the cached form.
     *
     * @param string  The cached query, in a serialized form.
     * @return array  The custom component that was cached together with the essential
     *                query data. This can be either a result set (result caching)
     *                or an SQL query string (query caching).
     */
    protected function _constructQueryFromCache($cached)
    {
        $cached = unserialize($cached);
        $this->_tableAliasMap = $cached[2];
        $customComponent = $cached[0];

        $queryComponents = array();
        $cachedComponents = $cached[1];
        foreach ($cachedComponents as $alias => $components) {
            $e = explode('.', $components[0]);
            if (count($e) === 1) {
                $queryComponents[$alias]['table'] = $this->_conn->getTable($e[0]);
            } else {
                $queryComponents[$alias]['parent'] = $e[0];
                $queryComponents[$alias]['relation'] = $queryComponents[$e[0]]['table']->getRelation($e[1]);
                $queryComponents[$alias]['table'] = $queryComponents[$alias]['relation']->getTable();
            }
            if (isset($components[1])) {
                $queryComponents[$alias]['agg'] = $components[1];
            }
            if (isset($components[2])) {
                $queryComponents[$alias]['map'] = $components[2];
            }
        }
        $this->_queryComponents = $queryComponents;

        return $customComponent;
    }

    /**
     * getCachedForm
     * returns the cached form of this query for given resultSet
     *
     * @param array $resultSet
     * @return string           serialized string representation of this query
     */
    public function getCachedForm($customComponent = null)
    {
        $componentInfo = array();

        foreach ($this->getQueryComponents() as $alias => $components) {
            if ( ! isset($components['parent'])) {
                $componentInfo[$alias][] = $components['table']->getComponentName();
            } else {
                $componentInfo[$alias][] = $components['parent'] . '.' . $components['relation']->getAlias();
            }
            if (isset($components['agg'])) {
                $componentInfo[$alias][] = $components['agg'];
            }
            if (isset($components['map'])) {
                $componentInfo[$alias][] = $components['map'];
            }
        }

        return serialize(array($customComponent, $componentInfo, $this->getTableAliasMap()));
    }

    /**
     * addSelect
     * adds fields to the SELECT part of the query
     *
     * @param string $select        Query SELECT part
     * @return Doctrine_Query
     */
    public function addSelect($select)
    {
        return $this->_addDqlQueryPart('select', $select, true);
    }

    /**
     * addTableAlias
     * adds an alias for table and associates it with given component alias
     *
     * @param string $componentAlias    the alias for the query component associated with given tableAlias
     * @param string $tableAlias        the table alias to be added
     * @return Doctrine_Hydrate
     * @deprecated
     */
    public function addTableAlias($tableAlias, $componentAlias)
    {
        return $this->addSqlTableAlias($tableAlias, $componentAlias);
    }

    /**
     * addSqlTableAlias
     * adds an SQL table alias and associates it a component alias
     *
     * @param string $componentAlias    the alias for the query component associated with given tableAlias
     * @param string $tableAlias        the table alias to be added
     * @return Doctrine_Query_Abstract
     */
    public function addSqlTableAlias($sqlTableAlias, $componentAlias)
    {
        $this->_tableAliasMap[$sqlTableAlias] = $componentAlias;
        return $this;
    }

    /**
     * addFrom
     * adds fields to the FROM part of the query
     *
     * @param string $from        Query FROM part
     * @return Doctrine_Query
     */
    public function addFrom($from)
    {
        return $this->_addDqlQueryPart('from', $from, true);
    }

    /**
     * addWhere
     * adds conditions to the WHERE part of the query
     *
     * @param string $where         Query WHERE part
     * @param mixed $params         an array of parameters or a simple scalar
     * @return Doctrine_Query
     */
    public function addWhere($where, $params = array())
    {
        if (is_array($params)) {
            $this->_params['where'] = array_merge($this->_params['where'], $params);
        } else {
            $this->_params['where'][] = $params;
        }
        return $this->_addDqlQueryPart('where', $where, true);
    }

    /**
     * whereIn
     * adds IN condition to the query WHERE part
     *
     * @param string $expr          the operand of the IN
     * @param mixed $params         an array of parameters or a simple scalar
     * @param boolean $not          whether or not to use NOT in front of IN
     * @return Doctrine_Query
     */
    public function whereIn($expr, $params = array(), $not = false)
    {
        $params = (array) $params;

        // if there's no params, return (else we'll get a WHERE IN (), invalid SQL)
        if (!count($params))
          return $this;

        $a = array();
        foreach ($params as $k => $value) {
            if ($value instanceof Doctrine_Expression) {
                $value = $value->getSql();
                unset($params[$k]);
            } else {
                $value = '?';
            }
            $a[] = $value;
        }

        $this->_params['where'] = array_merge($this->_params['where'], $params);

        $where = $expr . ($not === true ? ' NOT ':'') . ' IN (' . implode(', ', $a) . ')';

        return $this->_addDqlQueryPart('where', $where, true);
    }

    /**
     * whereNotIn
     * adds NOT IN condition to the query WHERE part
     *
     * @param string $expr          the operand of the NOT IN
     * @param mixed $params         an array of parameters or a simple scalar
     * @return Doctrine_Query
     */
    public function whereNotIn($expr, $params = array())
    {
        return $this->whereIn($expr, $params, true);
    }

    /**
     * addGroupBy
     * adds fields to the GROUP BY part of the query
     *
     * @param string $groupby       Query GROUP BY part
     * @return Doctrine_Query
     */
    public function addGroupBy($groupby)
    {
        return $this->_addDqlQueryPart('groupby', $groupby, true);
    }

    /**
     * addHaving
     * adds conditions to the HAVING part of the query
     *
     * @param string $having        Query HAVING part
     * @param mixed $params         an array of parameters or a simple scalar
     * @return Doctrine_Query
     */
    public function addHaving($having, $params = array())
    {
        if (is_array($params)) {
            $this->_params['having'] = array_merge($this->_params['having'], $params);
        } else {
            $this->_params['having'][] = $params;
        }
        return $this->_addDqlQueryPart('having', $having, true);
    }

    /**
     * addOrderBy
     * adds fields to the ORDER BY part of the query
     *
     * @param string $orderby       Query ORDER BY part
     * @return Doctrine_Query
     */
    public function addOrderBy($orderby)
    {
        return $this->_addDqlQueryPart('orderby', $orderby, true);
    }

    /**
     * select
     * sets the SELECT part of the query
     *
     * @param string $select        Query SELECT part
     * @return Doctrine_Query
     */
    public function select($select)
    {
        return $this->_addDqlQueryPart('select', $select);
    }

    /**
     * distinct
     * Makes the query SELECT DISTINCT.
     *
     * @param bool $flag            Whether or not the SELECT is DISTINCT (default true).
     * @return Doctrine_Query
     */
    public function distinct($flag = true)
    {
        $this->_sqlParts['distinct'] = (bool) $flag;
        return $this;
    }

    /**
     * forUpdate
     * Makes the query SELECT FOR UPDATE.
     *
     * @param bool $flag            Whether or not the SELECT is FOR UPDATE (default true).
     * @return Doctrine_Query
     */
    public function forUpdate($flag = true)
    {
        $this->_sqlParts[self::FOR_UPDATE] = (bool) $flag;
        return $this;
    }

    /**
     * delete
     * sets the query type to DELETE
     *
     * @return Doctrine_Query
     */
    public function delete()
    {
        $this->_type = self::DELETE;
        return $this;
    }

    /**
     * update
     * sets the UPDATE part of the query
     *
     * @param string $update        Query UPDATE part
     * @return Doctrine_Query
     */
    public function update($update)
    {
        $this->_type = self::UPDATE;
        return $this->_addDqlQueryPart('from', $update);
    }

    /**
     * set
     * sets the SET part of the query
     *
     * @param string $update        Query UPDATE part
     * @return Doctrine_Query
     */
    public function set($key, $value, $params = null)
    {
        if (is_array($key)) {
            foreach ($key as $k => $v) {
                $this->set($k, '?', array($v));
            }
            return $this;
        } else {
            if ($params !== null) {
                if (is_array($params)) {
                    $this->_params['set'] = array_merge($this->_params['set'], $params);
                } else {
                    $this->_params['set'][] = $params;
                }
            }

            $this->_pendingSetParams[$key] = $value;

            return $this->_addDqlQueryPart('set', $key . ' = ' . $value, true);
        }
    }

    /**
     * from
     * sets the FROM part of the query
     *
     * @param string $from          Query FROM part
     * @return Doctrine_Query
     */
    public function from($from)
    {
        return $this->_addDqlQueryPart('from', $from);
    }

    /**
     * innerJoin
     * appends an INNER JOIN to the FROM part of the query
     *
     * @param string $join         Query INNER JOIN
     * @return Doctrine_Query
     */
    public function innerJoin($join, $params = array())
    {
        if (is_array($params)) {
            $this->_params['join'] = array_merge($this->_params['join'], $params);
        } else {
            $this->_params['join'][] = $params;
        }

        return $this->_addDqlQueryPart('from', 'INNER JOIN ' . $join, true);
    }

    /**
     * leftJoin
     * appends a LEFT JOIN to the FROM part of the query
     *
     * @param string $join         Query LEFT JOIN
     * @return Doctrine_Query
     */
    public function leftJoin($join, $params = array())
    {
        if (is_array($params)) {
            $this->_params['join'] = array_merge($this->_params['join'], $params);
        } else {
            $this->_params['join'][] = $params;
        }

        return $this->_addDqlQueryPart('from', 'LEFT JOIN ' . $join, true);
    }

    /**
     * groupBy
     * sets the GROUP BY part of the query
     *
     * @param string $groupby      Query GROUP BY part
     * @return Doctrine_Query
     */
    public function groupBy($groupby)
    {
        return $this->_addDqlQueryPart('groupby', $groupby);
    }

    /**
     * where
     * sets the WHERE part of the query
     *
     * @param string $join         Query WHERE part
     * @param mixed $params        an array of parameters or a simple scalar
     * @return Doctrine_Query
     */
    public function where($where, $params = array())
    {
        $this->_params['where'] = array();
        if (is_array($params)) {
            $this->_params['where'] = $params;
        } else {
            $this->_params['where'][] = $params;
        }

        return $this->_addDqlQueryPart('where', $where);
    }

    /**
     * having
     * sets the HAVING part of the query
     *
     * @param string $having       Query HAVING part
     * @param mixed $params        an array of parameters or a simple scalar
     * @return Doctrine_Query
     */
    public function having($having, $params = array())
    {
        $this->_params['having'] = array();
        if (is_array($params)) {
            $this->_params['having'] = $params;
        } else {
            $this->_params['having'][] = $params;
        }

        return $this->_addDqlQueryPart('having', $having);
    }

    /**
     * orderBy
     * sets the ORDER BY part of the query
     *
     * @param string $orderby      Query ORDER BY part
     * @return Doctrine_Query
     */
    public function orderBy($orderby)
    {
        return $this->_addDqlQueryPart('orderby', $orderby);
    }

    /**
     * limit
     * sets the Query query limit
     *
     * @param integer $limit        limit to be used for limiting the query results
     * @return Doctrine_Query
     */
    public function limit($limit)
    {
        return $this->_addDqlQueryPart('limit', $limit);
    }

    /**
     * offset
     * sets the Query query offset
     *
     * @param integer $offset       offset to be used for paginating the query
     * @return Doctrine_Query
     */
    public function offset($offset)
    {
        return $this->_addDqlQueryPart('offset', $offset);
    }

    /**
     * getSql
     * shortcut for {@link getSqlQuery()}.
     * 
     * @param array $params (optional)
     * @return string   sql query string
     */
    public function getSql($params = array())
    {
        return $this->getSqlQuery($params);
    }

    /**
     * clear
     * resets all the variables
     *
     * @return void
     */
    protected function clear()
    {
        $this->_sqlParts = array(
                    'select'    => array(),
                    'distinct'  => false,
                    'forUpdate' => false,
                    'from'      => array(),
                    'set'       => array(),
                    'join'      => array(),
                    'where'     => array(),
                    'groupby'   => array(),
                    'having'    => array(),
                    'orderby'   => array(),
                    'limit'     => false,
                    'offset'    => false,
                    );
    }

    public function setHydrationMode($hydrationMode)
    {
        $this->_hydrator->setHydrationMode($hydrationMode);
        return $this;
    }

    /**
     * @deprecated
     */
    public function getAliasMap()
    {
        return $this->_queryComponents;
    }

    /**
     * Gets the components of this query.
     */
    public function getQueryComponents()
    {
        return $this->_queryComponents;
    }

    /**
     * Return the SQL parts.
     *
     * @return array The parts
     * @deprecated
     */
    public function getParts()
    {
        return $this->getSqlParts();
    }

    /**
     * Return the SQL parts.
     *
     * @return array The parts
     */
    public function getSqlParts()
    {
        return $this->_sqlParts;
    }

    /**
     * getType
     *
     * returns the type of this query object
     * by default the type is Doctrine_Query_Abstract::SELECT but if update() or delete()
     * are being called the type is Doctrine_Query_Abstract::UPDATE and Doctrine_Query_Abstract::DELETE,
     * respectively
     *
     * @see Doctrine_Query_Abstract::SELECT
     * @see Doctrine_Query_Abstract::UPDATE
     * @see Doctrine_Query_Abstract::DELETE
     *
     * @return integer      return the query type
     */
    public function getType()
    {
        return $this->_type;
    }

    /**
     * useCache
     *
     * @param Doctrine_Cache_Interface|bool $driver      cache driver
     * @param integer $timeToLive                        how long the cache entry is valid
     * @return Doctrine_Hydrate         this object
     * @deprecated Use useResultCache()
     */
    public function useCache($driver = true, $timeToLive = null)
    {
        return $this->useResultCache($driver, $timeToLive);
    }

    /**
     * useResultCache
     *
     * @param Doctrine_Cache_Interface|bool $driver      cache driver
     * @param integer $timeToLive                        how long the cache entry is valid
     * @return Doctrine_Hydrate         this object
     */
    public function useResultCache($driver = true, $timeToLive = null)
    {
        if ($driver !== null && $driver !== true && ! ($driver instanceOf Doctrine_Cache_Interface)) {
            $msg = 'First argument should be instance of Doctrine_Cache_Interface or null.';
            throw new Doctrine_Query_Exception($msg);
        }
        $this->_resultCache = $driver;

        return $this->setResultCacheLifeSpan($timeToLive);
    }

    /**
     * useQueryCache
     *
     * @param Doctrine_Cache_Interface|bool $driver      cache driver
     * @param integer $timeToLive                        how long the cache entry is valid
     * @return Doctrine_Hydrate         this object
     */
    public function useQueryCache(Doctrine_Cache_Interface $driver, $timeToLive = null)
    {
        $this->_queryCache = $driver;
        return $this->setQueryCacheLifeSpan($timeToLive);
    }

    /**
     * expireCache
     *
     * @param boolean $expire       whether or not to force cache expiration
     * @return Doctrine_Hydrate     this object
     * @deprecated Use expireResultCache()
     */
    public function expireCache($expire = true)
    {
        return $this->expireResultCache($expire);
    }

    /**
     * expireCache
     *
     * @param boolean $expire       whether or not to force cache expiration
     * @return Doctrine_Hydrate     this object
     */
    public function expireResultCache($expire = true)
    {
        $this->_expireResultCache = true;
        return $this;
    }

    /**
     * expireQueryCache
     *
     * @param boolean $expire       whether or not to force cache expiration
     * @return Doctrine_Hydrate     this object
     */
    public function expireQueryCache($expire = true)
    {
        $this->_expireQueryCache = true;
        return $this;
    }

    /**
     * setCacheLifeSpan
     *
     * @param integer $timeToLive   how long the cache entry is valid
     * @return Doctrine_Hydrate     this object
     * @deprecated Use setResultCacheLifeSpan()
     */
    public function setCacheLifeSpan($timeToLive)
    {
        return $this->setResultCacheLifeSpan($timeToLive);
    }

    /**
     * setResultCacheLifeSpan
     *
     * @param integer $timeToLive   how long the cache entry is valid (in seconds)
     * @return Doctrine_Hydrate     this object
     */
    public function setResultCacheLifeSpan($timeToLive)
    {
        if ($timeToLive !== null) {
            $timeToLive = (int) $timeToLive;
        }
        $this->_resultCacheTTL = $timeToLive;

        return $this;
    }

    /**
     * Gets the life span of the result cache in seconds.
     *
     * @return integer
     */
    public function getResultCacheLifeSpan()
    {
        return $this->_resultCacheTTL;
    }

    /**
     * setQueryCacheLifeSpan
     *
     * @param integer $timeToLive   how long the cache entry is valid
     * @return Doctrine_Hydrate     this object
     */
    public function setQueryCacheLifeSpan($timeToLive)
    {
        if ($timeToLive !== null) {
            $timeToLive = (int) $timeToLive;
        }
        $this->_queryCacheTTL = $timeToLive;

        return $this;
    }

    /**
     * Gets the life span of the query cache the Query object is using.
     *
     * @return integer  The life span in seconds.
     */
    public function getQueryCacheLifeSpan()
    {
        return $this->_queryCacheTTL;
    }

    /**
     * getCacheDriver
     * returns the cache driver associated with this object
     *
     * @return Doctrine_Cache_Interface|boolean|null    cache driver
     * @deprecated Use getResultCacheDriver()
     */
    public function getCacheDriver()
    {
        return $this->getResultCacheDriver();
    }

    /**
     * getResultCacheDriver
     * returns the cache driver used for caching result sets
     *
     * @return Doctrine_Cache_Interface|boolean|null    cache driver
     */
    public function getResultCacheDriver()
    {
        if ($this->_resultCache instanceof Doctrine_Cache_Interface) {
            return $this->_resultCache;
        } else {
            return $this->_conn->getResultCacheDriver();
        }
    }

    /**
     * getQueryCacheDriver
     * returns the cache driver used for caching queries
     *
     * @return Doctrine_Cache_Interface|boolean|null    cache driver
     */
    public function getQueryCacheDriver()
    {
        if ($this->_queryCache instanceof Doctrine_Cache_Interface) {
            return $this->_queryCache;
        } else {
            return $this->_conn->getQueryCacheDriver();
        }
    }

    /**
     * getConnection
     *
     * @return Doctrine_Connection
     */
    public function getConnection()
    {
        return $this->_conn;
    }

    /**
     * Adds a DQL part to the internal parts collection.
     *
     * @param string $queryPartName  The name of the query part.
     * @param string $queryPart      The actual query part to add.
     * @param boolean $append        Whether to append $queryPart to already existing
     *                               parts under the same $queryPartName. Defaults to FALSE
     *                               (previously added parts with the same name get overridden).
     */
    protected function _addDqlQueryPart($queryPartName, $queryPart, $append = false)
    {
        if ($append) {
            $this->_dqlParts[$queryPartName][] = $queryPart;
        } else {
            $this->_dqlParts[$queryPartName] = array($queryPart);
        }

        $this->_state = Doctrine_Query::STATE_DIRTY;
        return $this;
    }

    /**
     * _processDqlQueryPart
     * parses given query part
     *
     * @param string $queryPartName     the name of the query part
     * @param array $queryParts         an array containing the query part data
     * @return Doctrine_Query           this object
     * @todo Better description. "parses given query part" ??? Then wheres the difference
     *       between process/parseQueryPart? I suppose this does something different.
     */
    protected function _processDqlQueryPart($queryPartName, $queryParts)
    {
        $this->removeSqlQueryPart($queryPartName);

        if (is_array($queryParts) && ! empty($queryParts)) {
            foreach ($queryParts as $queryPart) {
                $parser = $this->_getParser($queryPartName);
                $sql = $parser->parse($queryPart);
                if (isset($sql)) {
                    if ($queryPartName == 'limit' || $queryPartName == 'offset') {
                        $this->setSqlQueryPart($queryPartName, $sql);
                    } else {
                        $this->addSqlQueryPart($queryPartName, $sql);
                    }
                }
            }
        }
    }

    /**
     * _getParser
     * parser lazy-loader
     *
     * @throws Doctrine_Query_Exception     if unknown parser name given
     * @return Doctrine_Query_Part
     * @todo Doc/Description: What is the parameter for? Which parsers are available?
     */
    protected function _getParser($name)
    {
        if ( ! isset($this->_parsers[$name])) {
            $class = 'Doctrine_Query_' . ucwords(strtolower($name));

            Doctrine::autoload($class);

            if ( ! class_exists($class)) {
                throw new Doctrine_Query_Exception('Unknown parser ' . $name);
            }

            $this->_parsers[$name] = new $class($this, $this->_tokenizer);
        }

        return $this->_parsers[$name];
    }

    /**
     * Gets the SQL query that corresponds to this query object.
     * The returned SQL syntax depends on the connection driver that is used
     * by this query object at the time of this method call.
     *
     * @param array $params
     */
    abstract public function getSqlQuery($params = array());

    /**
     * parseDqlQuery
     * parses a dql query
     *
     * @param string $query         query to be parsed
     * @return Doctrine_Query_Abstract  this object
     */
    abstract public function parseDqlQuery($query);

    /**
     * @deprecated
     */
    public function parseQuery($query)
    {
        return $this->parseDqlQuery($query);
    }

    /**
     * @deprecated
     */
    public function getQuery($params = array())
    {
        return $this->getSqlQuery($params);
    }
    /**
      * función auxiliar para saber longitud del from en 0
      * @return integer
      * 
      */
     public function LongitudFrom(){
          $tabla=$this->_dqlParts['from'][0];
              $tab=trim($tabla);
              $nombre=explode(' ', $tab);
              
              return count($nombre);
     }
     /**
      * función auxiliar que me devuelve el from en la posicion 0 en dos partes
      * @return array
      * 
      */
     public function FromPartes(){
          $tabla=$this->_dqlParts['from'][0];
              $tab=trim($tabla);
              $nombre=explode(' ', $tab);
              return $nombre;
     }
     
     
     /**
      *función para cargar las reglas asociadas al usuario en la tabla actual
      * con permiso a eliminar( DELETE)
      * 
      */
    public function ReglasEliminarTabla(){
         $xml = ZendExt_FastResponse::getXML('mongo');
        $recursos = array();
        foreach ($xml->children() as $recu) {           
            $instalado = (string)$recu['instalado'];            
        }
       if($instalado == 0)
        return array();
       
       $global = ZendExt_GlobalConcept::getInstance();      
       $idacl = $global->Perfil->iddominio;
       $idusuario=$global->Perfil->idusuario;
       $identidad = $global->Estructura->idestructura;
       $session = Zend_Registry::get('session');
       $rol = $session->idrol;  
       $rolacl=$idusuario.'_'.$rol.'_'.$identidad;
       $table='';
       $ta=null;
       if($this->LongitudFrom()==1){             
            $ta = Doctrine::getTable($this->getRootAlias());
            
            $table = $ta->getTableName();
            
            }else if($this->LongitudFrom()==2){
                $from=$this->FromPartes();
                $ta = Doctrine::getTable($from[0]);
                $table = $ta->getTableName();
                
                }
                if($ta!=null){
                $columns=$ta->getColumns();
       
        $mongo=new ZendExt_Mongo();
        $collection=$mongo->buscarPorIdAcl($idacl);
       
        $reglas=array();
        $tablita=explode('.',$table);
        $pos=count($tablita)-1;
        
       
        foreach ($collection as $tupla) {            
            if($tupla['rolacl']==$rolacl && $tupla['table_name']==$tablita[$pos] && $tupla['permiso']=='eliminar'){                 
                
                if(!empty ($tupla['nombreregla'])){
                 $reg=array();
                $reg['nombreregla'] = $tupla['nombreregla'];                
                $reg['campo'] = $tupla['campo'];                
                $reg['operador'] = $tupla['operador'];
                if($tupla['valor']=='true' && $columns[$tupla['campo']]['ntype']=='numeric'){
                   $reg['valor'] = 1;  
                }else if($tupla['valor']=='false' && $columns[$tupla['campo']]['ntype']=='numeric'){
                    $reg['valor'] = 0;
                }else{
                    $reg['valor'] = $tupla['valor'];
                }              
                
                $reg['table_name']=$tupla['table_name'];
                $reglas[]=$reg;
                }
            }
        }
        
        return $reglas;
         }
         return array();
        
     }
      /**
      *función para cargar las reglas asociadas al usuario en la tabla actual
      * con permiso a eliminar(DELETE).
      * Quirog@
      * Agregado para cargar las reglas guardadas en Cache(Implementacion de XACML)
      */
     public function ObtenerReglasEliminar() {
        $xml = ZendExt_FastResponse::getXML('mongo');
        $recursos = array();
        foreach ($xml->children() as $recu) {           
            $instalado = (string)$recu['instalado'];            
        }
       if($instalado == 0)
        return array();       
       $table='';
       $ta=null;
       if($this->LongitudFrom()==1){             
            $ta = Doctrine::getTable($this->getRootAlias());            
            $table = $ta->getTableName();            
            }else if($this->LongitudFrom()==2){
                $from=$this->FromPartes();
                $ta = Doctrine::getTable($from[0]);
                $table = $ta->getTableName();
                
                }
                if($ta!=null){
                $columns=$ta->getColumns();
              
        $objCache= ZendExt_Cache::getInstance();	
        $obligaciones= $objCache->load('obligaciones');
        $permisos=$obligaciones['permisos'];
        
        $reglas=array();
        $tablita=explode('.',$table);
        $pos=count($tablita)-1;
        
        foreach ($permisos as $tupla) { 
            $tupla=get_object_vars($tupla);
            if($tupla['table_name']==$tablita[$pos] && $tupla['permiso']=='eliminar'){                 
                
                if(!empty ($tupla['nombreregla'])){
                 $reg=array();
                $reg['nombreregla'] = $tupla['nombreregla'];                
                $reg['campo'] = $tupla['campo'];  
                if($tupla['operador']=='diferente'){
                       $tupla['operador']='<>';  
                    }  elseif ($tupla['operador']=='menorigual') {
                       $tupla['operador']='<='; 
                    } 
                $reg['operador'] = $tupla['operador'];
                if($tupla['valor']=='true' && $columns[$tupla['campo']]['ntype']=='numeric'){
                   $reg['valor'] = 1;  
                }else if($tupla['valor']=='false' && $columns[$tupla['campo']]['ntype']=='numeric'){
                    $reg['valor'] = 0;
                }else{
                    $reg['valor'] = $tupla['valor'];
                }              
                
                $reg['table_name']=$tupla['table_name'];
                $reglas[]=$reg;
                }
            }
        }
        
        return $reglas;
         }
         return array();
     }


   /*
   *función que me filtra el SELECT segun las reglas
   * Quirog@
   * Modificado para cargar las reglas guardadas en Cache(Implementacion de XACML)
   */
  public function FiltroSelect(){
      
                $from=$this->_dqlParts['from'];
               
               if(count($from)>0){
                   
                 for($i=0;$i<count($from);$i++){ 
                  $tabla=$this->Preparartabla($from[$i]); 
                  $alias=$this->configurarAlias($from[$i]); 
                  $tab=Doctrine::getTable($tabla);
                  $tableName=$this->Preparartabla($tab->getTableName());
                 
                 if(count($this->ObtenerReglaSelect($tableName))>0){
                     if($i==0){
                     $reglas=$this->ObtenerReglaSelect($tableName);
                   
                     foreach($reglas as $re){
                         
                         if($alias!=0){                      
                             
                        $this->addWhere($alias.'.'.$re['campo'].$re['operador'].' ?', $re['valor']);
                                                   
                         }else{
                             
                         $this->addWhere($re['campo'].$re['operador'].' ?', $re['valor']);    
                         }                        
                     }
                     
                     }else{    
                  $tabla=$this->Preparartabla($from[$i-1]); 
                  $alias=$this->configurarAlias($from[$i-1]); 
                  $tab=Doctrine::getTable($tabla);
                  $tableName=$this->Preparartabla($tab->getTableName());
                  
                   if(count($this->ObtenerReglaSelect($tableName))>0){
                      $reglas=$this->ObtenerReglaSelect($tableName);
                      
                     foreach($reglas as $re){
                         
                         if($alias!=0){                      
                             
                        $this->addWhere($alias.'.'.$re['campo'].$re['operador'].' ?', $re['valor']);
                           
                        
                         }else{
                             
                         $this->addWhere($re['campo'].$re['operador'].' ?', $re['valor']);    
                         }
                        
                     } 
                   }
                     }
                   
                     
                 }
                 
                 }
                 }
    }

  /*
   *función que me devuelve el alias de la tabla de la consulta Ej SegUsuario u devuelve (u)
   *
   */


   public function configurarAlias($tableFromDefinition){
        $tableFrom=(String)$tableFromDefinition;
        
        $tabla="";
        $alias="";
        
        if(strlen($tableFrom)>11){
            $innerJoin.="";
            $leftJoin.="";
            for($i=0;$i<11;$i++){
               $innerJoin.=$tableFrom[$i]; 
            }
            
            for($i=0;$i<10;$i++){
               $leftJoin.=$tableFrom[$i]; 
            }
         
          
          if((String)$innerJoin=="INNER JOIN "){
              
             $tabla.="";
              for($i=11;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  
              }
              $tabla=trim($tabla);
              $con=0;
              $tabla=trim($tabla);
              for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $con++;
                  }
              }
              if($cont==0){
                  $alias=0;
              }else{
                  if($cont==1){
                      $pos=0;
                      for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $pos=$i+1;
                  }
              }
              for($i==$pos;$i<strlen($tabla);$i++){
                  
                      $alias.=$tabla[$i];
                  
              }
                      
                  }else{
                      $pos=0;
                      for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $pos=$i+1;
                  }
                  if($pos!=0)
                      break;
              }
              for($i==$pos;$i<strlen($tabla);$i++){
                      if($tabla[$i]!=' ')
                      $alias.=$tabla[$i];
                      if($tabla[$i]==' ')
                          break;
                  
              }
              
              if($alias=="ON"){
                  $alias==0;
              }else{
                  $temp=$alias;
                  $alias=$temp;
              }
                  }
              }
              
          }
          
          else if((String)$leftJoin=="LEFT JOIN "){
             $tabla.="";
              for($i=10;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  
              }
              $tabla=trim($tabla);
              $con=0;
              $tabla=trim($tabla);
              for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $con++;
                  }
              }
              if($cont==0){
                  $alias=0;
              }else{
                  if($cont==1){
                      $pos=0;
                      for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $pos=$i+1;
                  }
              }
              for($i==$pos;$i<strlen($tabla);$i++){
                  
                      $alias.=$tabla[$i];
                  
              }
                      
                  }else{
                      $pos=0;
                      for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $pos=$i+1;
                  }
                  if($pos!=0)
                      break;
              }
              for($i==$pos;$i<strlen($tabla);$i++){
                      if($tabla[$i]!=' ')
                      $alias.=$tabla[$i];
                      if($tabla[$i]==' ')
                          break;
                  
              }
              
              if($alias=="ON"){
                  $alias==0;
              }else{
                  $temp=$alias;
                  $alias=$temp;
              }
                  }
              }
              
              
             
          }
        else{
            $tabla.="";
              for($i=0;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  
              }
              $pos=0;
              $tabla=trim($tabla);
              for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $pos=$i;
                  }
              }
              
              if($pos==0){
                  $alias=0;
              }else{
                  for($i==$pos+1;$i<strlen($tabla);$i++){
                  $alias.=$tabla[$i];
              }
              }
              
        }
            
        }else{
            $tabla.="";
              for($i=0;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  
              }
              $pos=0;
              $tabla=trim($tabla);
              for($i==0;$i<strlen($tabla);$i++){
                  if($tabla[$i]==' '){
                      $pos=$i;
                  }
              }
              
              if($pos==0){
                  $alias=0;
              }else{
                  for($i==$pos+1;$i<strlen($tabla);$i++){
                  $alias.=$tabla[$i];
              }
              }
              
              
        }
        
        return $alias;
        
        
         
    }

   /*
   *función que me devuelve el nombre de la tabla del from sin alias o sea SegUsuario u devuelve SegUsuario
   *
   */

   public function Preparartabla($tableFromDefinition){
        
        $tableFrom=(String)$tableFromDefinition;
        
        
        
        if(strlen($tableFrom)>11){
            $innerJoin.="";
            $leftJoin.="";
            for($i=0;$i<11;$i++){
               $innerJoin.=$tableFrom[$i]; 
            }
            
            for($i=0;$i<10;$i++){
               $leftJoin.=$tableFrom[$i]; 
            }
         
          
          if((String)$innerJoin=="INNER JOIN "){
             $tabla.="";
              for($i=11;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  if($tableFrom[$i]==' ')
                      break;
              }
              $tabla=trim($tabla);
              return $this->QuitarConcatenador((String)$tabla);
          }
          
          else if((String)$leftJoin=="LEFT JOIN "){
             $tabla.="";
              for($i=10;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  if($tableFrom[$i]==' ')
                      break;
              }
              $tabla=trim($tabla);
              return $this->QuitarConcatenador((String)$tabla);
          }
        else{
            $tabla.="";
              for($i=0;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  if($tableFrom[$i]==' ')
                      break;
              }
              $tabla=trim($tabla);
              return $this->QuitarConcatenador((String)$tabla);
        }
        
        }else{
            $tabla.="";
              for($i=0;$i<strlen($tableFrom);$i++){
                  $tabla.=$tableFrom[$i];
                  if($tableFrom[$i]==' ')
                      break;
              }
              $tabla=trim($tabla);
              return $this->QuitarConcatenador((String)$tabla);
        }
     
    }


   /*
   *función que me quita los (.) de la parte del from Ej mod_seguridad.seg_usuario me devuelve la ultima parte (seg_usuario)
   *
   */

   public function QuitarConcatenador($cadena){
        $result="";
        
        $pos=0;
        for($i=0;$i<strlen($cadena);$i++){
           if($cadena[$i]=='.')
               $pos=$i+1;
        }
        
        for($i=$pos;$i<strlen($cadena);$i++){
           
               $result.=$cadena[$i];
        }
        
        
        return (String)$result;
    }



   /*
   *función que configura el rol de la acl para buscar el mongo concatenando el idusuario_idrol_identidad
   *
   */

   public function ConfigurarRolAcl(){
       $global = ZendExt_GlobalConcept::getInstance(); 
       $idusuario=$global->Perfil->idusuario;
       $identidad = $global->Estructura->idestructura;
       $session = Zend_Registry::get('session');
       $rol = $session->idrol;
       $rolacl=$idusuario.'_'.$rol.'_'.$identidad;
       return $rolacl;
    }


   /*
   *función que me vevuelve las reglas para ver la tabla del rolacl--> idusuario_idrol_identidad
   *
   */

    public function ReglasRolAclVerTable($rolacl,$tabla){
        
        $global = ZendExt_GlobalConcept::getInstance();
        $idacl = $global->Perfil->iddominio;
        $mongo=new ZendExt_Mongo();
        $reglas=$mongo->buscarReglasPermisoRolAcl($idacl, $rolacl, 'ver', $tabla);
               
        return $reglas;
        
    }
     /**
      *función para cargar las reglas asociadas al usuario en la tabla actual
      * con permiso a seleccionar(SELECT).
      * Quirog@
      * Agregado para cargar las reglas guardadas en Cache(Implementacion de XACML)
      */
    public function ObtenerReglaSelect($tabla) {
        $objCache= ZendExt_Cache::getInstance();	
        $obligaciones= $objCache->load('obligaciones');
        $permisos=$obligaciones['permisos'];
        $reglas=array();
           foreach ($permisos as $perm) {  
                $perm=get_object_vars($perm);
            if($perm['permiso']=='ver' && $perm['table_name']==$tabla)
               if(isset($perm['nombreregla'])){      
               $reg['campo']=$perm['campo'];
               if($perm['operador']=='diferente'){
                       $perm['operador']='<>';  
                    }  elseif ($perm['operador']=='menorigual') {
                       $perm['operador']='<='; 
                    }
               $reg['operador']=$perm['operador'];
               $reg['valor']=$perm['valor'];
               $reglas[]=$reg;
               }
            }
            return $reglas;
    }

}