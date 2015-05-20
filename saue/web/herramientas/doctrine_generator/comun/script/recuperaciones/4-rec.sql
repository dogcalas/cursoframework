--------------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
--SCRIPT de INSTALACION de PERMISOS 	----
--										----
*/
----------------------------------------
------------------------------------------------------------------------------------------------
/*
--Reglas de confidencialidad																----
--Clasificación: Clasificado.																----
--Forma de distribución: Script SQL.														----
--Este documento contiene información propietaria del CENTRO DE SOLUCIONES DE GESTIÓN 		----
--y es emitido confidencialmente para un propósito específico. 								----
--El que recibe el documento asume la custodia y control, comprometiéndose a no reproducir, ----
--divulgar, difundir o de cualquier manera hacer de conocimiento público su contenido, 		----
--excepto para cumplir el propósito para el cual se ha generado.							----
--Las reglas son aplicables a todo este documento.											----
*/																							
------------------------------------------------------------------------------------------------

--comienza la transaccion--
begin;
-- Propiedades de la BD       
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;

INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_recuperaciones',null,'C','P','I');
----------------------------------------------------------------------------
--Permisos del rol reporte sobre el esquema mod_recuperaciones
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;

GRANT USAGE
  ON SCHEMA "mod_recuperaciones" TO reportesigis;

----------------------------------------
-- Permisos sobre secuencias
----------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;

GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.ncss_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.nformat_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.noptionrw_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.nrol_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.nscheduletype_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.nsubscriptiontype_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.ntargetdb_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.ntask_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbcategory_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbconcurrency_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbdatasource_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbmodel_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbreport_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbschedule_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbsqlerror_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbsqlfunction_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbsubscription_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbtemplate_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbuser_seq TO reportesigis;
GRANT SELECT, UPDATE, USAGE ON mod_recuperaciones.tbversion_seq TO reportesigis;

----------------------------------------
--Permisos sobre tablas
----------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;

GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.ncss TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.nformat TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.noptionrw TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.nrol TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.nscheduletype TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.nsubscriptiontype TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.ntargetdb TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.ntask TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbcategory TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbcategoryreport TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbconcurrency TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbdatasource TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbmodel TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbreport TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbreportdatasource TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbreportmodel TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbrolcategory TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbrolreport TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbroltask TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbschedule TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbsqlerror TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbsqlfunction TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbsubscription TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbtemplate TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbtemplatecategory TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbtemplatecss TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbuser TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbusercategory TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbuserreport TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbuserrol TO reportesigis;
GRANT SELECT, UPDATE, INSERT, DELETE, REFERENCES, TRIGGER ON TABLE mod_recuperaciones.tbversion TO reportesigis;

----------------------------------------
--Permisos sobre funciones
----------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;


----------------------------------------

----------------------------------------------------------------------------
--Permisos del rol erp sobre el esquema mod_recuperaciones
----------------------------------------------------------------------------
----------------------------------------
--Permisos sobre el esquema
----------------------------------------
	SET search_path = mod_recuperaciones, pg_catalog;
	
ALTER SCHEMA "mod_recuperaciones" OWNER TO reportesigis;
----------------------------------------
ALTER TABLE mod_recuperaciones.ncss_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.nformat_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.noptionrw_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.nrol_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.nscheduletype_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.nsubscriptiontype_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.ntargetdb_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.ntask_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbcategory_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbconcurrency_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbdatasource_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbmodel_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbreport_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbschedule_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbsqlerror_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbsqlfunction_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbsubscription_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbtemplate_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbuser_seq OWNER TO reportesigis;
ALTER TABLE mod_recuperaciones.tbversion_seq OWNER TO reportesigis;



ALTER SCHEMA "mod_recuperaciones"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."ncss"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."nformat"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."noptionrw"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."nrol"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."nscheduletype"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."nsubscriptiontype"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."ntargetdb"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."ntask"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbcategory"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbcategoryreport"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbconcurrency"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbdatasource"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbmodel"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbreport"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbreportdatasource"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbreportmodel"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbrolcategory"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbrolreport"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbroltask"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbschedule"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbsqlerror"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbsqlfunction"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbsubscription"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbtemplate"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbtemplatecategory"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbtemplatecss"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbuser"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbusercategory"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbuserreport"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbuserrol"
  OWNER TO reportesigis;
ALTER TABLE "mod_recuperaciones"."tbversion"
  OWNER TO reportesigis;




----------------------------------------
--fin de la transaccion
COMMIT;