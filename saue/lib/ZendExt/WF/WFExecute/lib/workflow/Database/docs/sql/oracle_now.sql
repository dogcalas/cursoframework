CREATE OR REPLACE FUNCTION NOW RETURN TIMESTAMP IS
/**
* Get the current date
*/
BEGIN
  RETURN (SYSDATE);
END;
/
exit;
      