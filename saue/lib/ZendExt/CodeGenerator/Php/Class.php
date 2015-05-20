<?php

/**
 * @see ZendExt_CodeGenerator_Php_Abstract
 */
require_once 'ZendExt/CodeGenerator/Php/Abstract.php';

/**
 * @see ZendExt_CodeGenerator_Php_Member_Container
 */
require_once 'ZendExt/CodeGenerator/Php/Member/Container.php';

/**
 * @see ZendExt_CodeGenerator_Php_Method
 */
require_once 'ZendExt/CodeGenerator/Php/Method.php';

/**
 * @see ZendExt_CodeGenerator_Php_Property
 */
require_once 'ZendExt/CodeGenerator/Php/Property.php';

/**
 * @see ZendExt_CodeGenerator_Php_Docblock
 */
require_once 'ZendExt/CodeGenerator/Php/Docblock.php';

/**
 * Description of Class
 *
 * @author Inoelkis Velazquez Osorio
 */
class ZendExt_CodeGenerator_Php_Class extends ZendExt_CodeGenerator_Php_Abstract {

    /**
     * @var ZendExt_CodeGenerator_Php_Docblock
     */
    protected $_docblock = null;

    /**
     * @var string
     */
    protected $_name = null;

    /**
     * @var string
     */
    protected $_namespace = null;

    /**
     * @var string
     */
    protected $_use = null;

    /**
     * @var bool
     */
    protected $_isAbstract = false;

    /**
     * @var string
     */
    protected $_extendedClass = null;

    /**
     * @var array Array of string names
     */
    protected $_implementedInterfaces = array();

    /**
     * @var array Array of properties
     */
    protected $_properties = null;

    /**
     * @var array Array of methods
     */
    protected $_methods = null;

    /**
     * fromReflection() - build a Code Generation PHP Object from a Class Reflection
     *
     * @param Zend_Reflection_Class $reflectionClass
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public static function fromReflection(Zend_Reflection_Class $reflectionClass) {
        $class = new self();

        $class->setSourceContent($class->getSourceContent());
        $class->setSourceDirty(false);

        if ($reflectionClass->getDocComment() != '') {
            $class->setDocblock(ZendExt_CodeGenerator_Php_Docblock::fromReflection($reflectionClass->getDocblock()));
        }

        $class->setAbstract($reflectionClass->isAbstract());
        $class->setName($reflectionClass->getName());

        if ($parentClass = $reflectionClass->getParentClass()) {
            $class->setExtendedClass($parentClass->getName());
            $interfaces = array_diff($reflectionClass->getInterfaces(), $parentClass->getInterfaces());
        } else {
            $interfaces = $reflectionClass->getInterfaces();
        }

        $interfaceNames = array();
        foreach ($interfaces AS $interface) {
            $interfaceNames[] = $interface->getName();
        }

        $class->setImplementedInterfaces($interfaceNames);

        $properties = array();
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            if ($reflectionProperty->getDeclaringClass()->getName() == $class->getName()) {
                $properties[] = ZendExt_CodeGenerator_Php_Property::fromReflection($reflectionProperty);
            }
        }
        $class->setProperties($properties);

        $methods = array();
        foreach ($reflectionClass->getMethods() as $reflectionMethod) {
            if ($reflectionMethod->getDeclaringClass()->getName() == $class->getName()) {
                $methods[] = ZendExt_CodeGenerator_Php_Method::fromReflection($reflectionMethod);
            }
        }
        $class->setMethods($methods);

        return $class;
    }

    /**
     * setDocblock() Set the docblock
     *
     * @param ZendExt_CodeGenerator_Php_Docblock|array|string $docblock
     * @return ZendExt_CodeGenerator_Php_File
     */
    public function setDocblock($docblock) {
        if (is_string($docblock)) {
            $docblock = array('shortDescription' => $docblock);
        }

        if (is_array($docblock)) {
            $docblock = new ZendExt_CodeGenerator_Php_Docblock($docblock);
        } elseif (!$docblock instanceof ZendExt_CodeGenerator_Php_Docblock) {
            require_once 'ZendExt/CodeGenerator/Php/Exception.php';
            throw new ZendExt_CodeGenerator_Php_Exception('setDocblock() is expecting either a string, array or an instance of ZendExt_CodeGenerator_Php_Docblock');
        }

        $this->_docblock = $docblock;
        return $this;
    }

    /**
     * getDocblock()
     *
     * @return ZendExt_CodeGenerator_Php_Docblock
     */
    public function getDocblock() {
        return $this->_docblock;
    }

    /**
     * setName()
     *
     * @param string $name
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setName($name) {
        $this->_name = $name;
        return $this;
    }

    /**
     * setNamespace()
     *
     * @param string $name
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setNamespace($name) {
        $this->_namespace = $name;
        return $this;
    }

    /**
     * setNamespace()
     *
     * @param string $use
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setUse($use) {
        $this->_use = $use;
        return $this;
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName() {
        return $this->_name;
    }

    /**
     * getNamespace()
     *
     * @return string
     */
    public function getNamespace() {
        return $this->_namespace;
    }

    /**
     * getUse()
     *
     * @return string
     */
    public function getUse() {
        return $this->_use;
    }

    /**
     * setAbstract()
     *
     * @param bool $isAbstract
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setAbstract($isAbstract) {
        $this->_isAbstract = ($isAbstract) ? true : false;
        return $this;
    }

    /**
     * isAbstract()
     *
     * @return bool
     */
    public function isAbstract() {
        return $this->_isAbstract;
    }

    /**
     * setExtendedClass()
     *
     * @param string $extendedClass
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setExtendedClass($extendedClass) {
        $this->_extendedClass = $extendedClass;
        return $this;
    }

    /**
     * getExtendedClass()
     *
     * @return string
     */
    public function getExtendedClass() {
        return $this->_extendedClass;
    }

    /**
     * setImplementedInterfaces()
     *
     * @param array $implementedInterfaces
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setImplementedInterfaces(Array $implementedInterfaces) {
        $this->_implementedInterfaces = $implementedInterfaces;
        return $this;
    }

    /**
     * getImplementedInterfaces
     *
     * @return array
     */
    public function getImplementedInterfaces() {
        return $this->_implementedInterfaces;
    }

    /**
     * setProperties()
     *
     * @param array $properties
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setProperties(Array $properties) {
        foreach ($properties as $property) {
            $this->setProperty($property);
        }

        return $this;
    }

    /**
     * setProperty()
     *
     * @param array|ZendExt_CodeGenerator_Php_Property $property
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setProperty($property) {
        if (is_array($property)) {
            $property = new ZendExt_CodeGenerator_Php_Property($property);
            $propertyName = $property->getName();
        } elseif ($property instanceof ZendExt_CodeGenerator_Php_Property) {
            $propertyName = $property->getName();
        } else {
            require_once 'ZendExt/CodeGenerator/Php/Exception.php';
            throw new ZendExt_CodeGenerator_Php_Exception('setProperty() expects either an array of property options or an instance of ZendExt_CodeGenerator_Php_Property');
        }

        if (isset($this->_properties[$propertyName])) {
            require_once 'ZendExt/CodeGenerator/Php/Exception.php';
            throw new ZendExt_CodeGenerator_Php_Exception('A property by name ' . $propertyName . ' already exists in this class.');
        }

        $this->_properties[$propertyName] = $property;
        return $this;
    }

    /**
     * getProperties()
     *
     * @return array
     */
    public function getProperties() {
        return $this->_properties;
    }

    /**
     * getProperty()
     *
     * @param string $propertyName
     * @return ZendExt_CodeGenerator_Php_Property
     */
    public function getProperty($propertyName) {
        foreach ($this->_properties as $property) {
            if ($property->getName() == $propertyName) {
                return $property;
            }
        }
        return false;
    }

    /**
     * hasProperty()
     *
     * @param string $propertyName
     * @return bool
     */
    public function hasProperty($propertyName) {
        return isset($this->_properties[$propertyName]);
    }

    /**
     * setMethods()
     *
     * @param array $methods
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setMethods(Array $methods) {
        foreach ($methods as $method) {
            $this->setMethod($method);
        }
        return $this;
    }

    /**
     * setMethod()
     *
     * @param array|ZendExt_CodeGenerator_Php_Method $method
     * @return ZendExt_CodeGenerator_Php_Class
     */
    public function setMethod($method) {
        if (is_array($method)) {
            $method = new ZendExt_CodeGenerator_Php_Method($method);
            $methodName = $method->getName();
        } elseif ($method instanceof ZendExt_CodeGenerator_Php_Method) {
            $methodName = $method->getName();
        } else {
            require_once 'ZendExt/CodeGenerator/Php/Exception.php';
            throw new ZendExt_CodeGenerator_Php_Exception('setMethod() expects either an array of method options or an instance of ZendExt_CodeGenerator_Php_Method');
        }

        if (isset($this->_methods[$methodName])) {
            require_once 'ZendExt/CodeGenerator/Php/Exception.php';
            throw new ZendExt_CodeGenerator_Php_Exception('A method by name ' . $methodName . ' already exists in this class.');
        }

        $this->_methods[$methodName] = $method;
        return $this;
    }

    /**
     * getMethods()
     *
     * @return array
     */
    public function getMethods() {
        return $this->_methods;
    }

    /**
     * getMethod()
     *
     * @param string $methodName
     * @return ZendExt_CodeGenerator_Php_Method
     */
    public function getMethod($methodName) {
        foreach ($this->_methods as $method) {
            if ($method->getName() == $methodName) {
                return $method;
            }
        }
        return false;
    }

    /**
     * hasMethod()
     *
     * @param string $methodName
     * @return bool
     */
    public function hasMethod($methodName) {
        return isset($this->_methods[$methodName]);
    }

    /**
     * isSourceDirty()
     *
     * @return bool
     */
    public function isSourceDirty() {
        if (($docblock = $this->getDocblock()) && $docblock->isSourceDirty()) {
            return true;
        }

        foreach ($this->_properties as $property) {
            if ($property->isSourceDirty()) {
                return true;
            }
        }

        foreach ($this->_methods as $method) {
            if ($method->isSourceDirty()) {
                return true;
            }
        }

        return parent::isSourceDirty();
    }

    /**
     * generate()
     *
     * @return string
     */
    public function generate() {
        if (!$this->isSourceDirty()) {
            return $this->getSourceContent();
        }

        $output = '';
        if (null != $this->getNamespace()) {
            $output .= "namespace ".$this->getNamespace();
        }
       
        if (null != $this->getUse()) {
            $output .= "use ".$this->getUse();
        }
        if (null !== ($docblock = $this->getDocblock())) {
            $docblock->setIndentation('');
            $output .= $docblock->generate();
        }


        if ($this->isAbstract()) {
            $output .= 'abstract ';
        }

        $output .= 'class ' . $this->getName();

        if (!empty($this->_extendedClass)) {
            $output .= ' extends ' . $this->_extendedClass;
        }

        $implemented = $this->getImplementedInterfaces();
        if (!empty($implemented)) {
            $output .= ' implements ' . implode(', ', $implemented);
        }

        $output .= self::LINE_FEED . '{' . self::LINE_FEED . self::LINE_FEED;

        $properties = $this->getProperties();
        if (!empty($properties)) {
            foreach ($properties as $property) {
                $output .= $property->generate() . self::LINE_FEED . self::LINE_FEED;
            }
        }

        $methods = $this->getMethods();
        if (!empty($methods)) {
            foreach ($methods as $method) {
                $output .= $method->generate() . self::LINE_FEED;
            }
        }

        $output .= self::LINE_FEED . '}' . self::LINE_FEED;

        return $output;
    }

    /**
     * _init() - is called at construction time
     *
     */
    protected function _init() {
        $this->_properties = new ZendExt_CodeGenerator_Php_Member_Container(ZendExt_CodeGenerator_Php_Member_Container::TYPE_PROPERTY);
        $this->_methods = new ZendExt_CodeGenerator_Php_Member_Container(ZendExt_CodeGenerator_Php_Member_Container::TYPE_METHOD);
    }

}
