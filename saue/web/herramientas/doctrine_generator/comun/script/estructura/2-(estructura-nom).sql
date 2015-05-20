--------------------V5.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Subdirección de tecnología			----
--										----
--SCRIPT de INSTALACION de ESTRUCTURA	----
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

--comienza la tranzaccion--
begin;

-- Propiedades de la BD
	SET client_encoding = 'UTF8';
	SET standard_conforming_strings = off;
	SET check_function_bodies = false;
	SET client_min_messages = warning;
	SET escape_string_warning = off;

	
INSERT INTO mod_datosmaestros.conf_version ( versionscript,  versionscriptinicial,  esquema,  esquemarelacion,  tiposcript,  classcript,  script) 
VALUES ('V5.0.0', 'V5.0.0','mod_estructuracomp','mod_nomencladores','C','E','I');
-------------------------------------------------------------------------------
----Creación de llaves foraneas
--------------------------------------------------------------------------------
SET search_path = mod_estructuracomp, pg_catalog;
	
ALTER TABLE ONLY dat_estructura
    ADD CONSTRAINT dat_estructura_fk FOREIGN KEY (idespecialidad) REFERENCES mod_nomencladores.nom_especialidad(idespecialidad);
	
ALTER TABLE ONLY dat_estructura
    ADD CONSTRAINT fk_nom_dpa FOREIGN KEY (iddpa) REFERENCES mod_nomencladores.nom_dpa(iddpa);

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_nom_dpa FOREIGN KEY (iddpa) REFERENCES mod_nomencladores.nom_dpa(iddpa);

ALTER TABLE ONLY dat_estructuraop
    ADD CONSTRAINT fk_nom_especialidad FOREIGN KEY (idespecialidad) REFERENCES mod_nomencladores.nom_especialidad(idespecialidad);

ALTER TABLE ONLY nom_cargocivil
    ADD CONSTRAINT fk_nom_especialidad FOREIGN KEY (idespecialidad) REFERENCES mod_nomencladores.nom_especialidad(idespecialidad);

ALTER TABLE ONLY nom_cargomilitar
    ADD CONSTRAINT fk_nom_especialidad FOREIGN KEY (idespecialidad) REFERENCES mod_nomencladores.nom_especialidad(idespecialidad);

ALTER TABLE ONLY dat_cargo
    ADD CONSTRAINT fk_nom_especialidad FOREIGN KEY (idespecialidad) REFERENCES mod_nomencladores.nom_especialidad(idespecialidad);

	
ALTER TABLE ONLY nom_organo
    ADD CONSTRAINT nom_organo_fk FOREIGN KEY (idespecialidad) REFERENCES mod_nomencladores.nom_especialidad(idespecialidad);

-------------------------------------------------------------------------------
----Creación de funciones
--------------------------------------------------------------------------------
SET search_path = mod_estructuracomp, pg_catalog;

CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_relacion_localizacion_unidades_nivelest("NombrePadre" character varying, "Codigo" character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_relacion_localizacion_unidades_nivelest AS
$BODY$
/* Firmado, le falta el DPA  */


declare
Result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;

CampoDomicilio numeric;
CampoLocalidad numeric;
CampoTelefono numeric;
CampoTelefonoDireccion numeric;
CampoFax numeric;
CampoEmail numeric;
tipo1 numeric;

BEGIN
id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =mod_estructuracomp."f_rep_buscarideav"('Unidad');

tipo1     =     (select organo.idorgano
                 from mod_estructuracomp.nom_organo organo
                 where organo.denorgano    =    'Tipo1');

CampoDomicilio=mod_estructuracomp."f_buscaridcampo"('Domicilio',EavUnidad);
CampoLocalidad=mod_estructuracomp."f_buscaridcampo"('localidad',EavUnidad);
CampoTelefono=mod_estructuracomp."f_buscaridcampo"('Telefpizarra',EavUnidad);
CampoTelefonoDireccion=mod_estructuracomp."f_buscaridcampo"('telefdir',EavUnidad);
CampoFax=mod_estructuracomp."f_buscaridcampo"('FAX',EavUnidad);
CampoEmail=mod_estructuracomp."f_buscaridcampo"('email',EavUnidad);

FOR Result IN
SELECT

       h.denominacion,
       h.abreviatura,
       v.valor,
       v1.valor,
       dpa1.denominacion,--dpa.denominacion,
       dpa.denominacion,--dpa1.denominacion,
       v2.valor,
       v3.valor,
       v4.valor,
       v5.valor

FROM

     mod_estructuracomp.dat_estructura p
inner join
     mod_estructuracomp.dat_estructura h
on
     h.lft>p.lft and h.lft<p.rgt
inner join
     mod_estructuracomp.nom_filaestruc f
on
h.idestructura=f.idfila
LEFT join
     mod_estructuracomp.nom_valorestruc v
on
     (v.idfila=f.idfila
       and v.idcampo= CampoDomicilio)
left join
     mod_estructuracomp.nom_valorestruc v1
on
    ( v1.idfila=f.idfila
     and v1.idcampo=CampoLocalidad)
left join
     mod_estructuracomp.nom_valorestruc v2
on
    ( v2.idcampo=CampoTelefono
       and v2.idfila=f.idfila)
left join
     mod_estructuracomp.nom_valorestruc v4
on
     ( v4.idfila=f.idfila
       and v4.idcampo=CampoTelefonoDireccion)
left JOIN
     mod_estructuracomp.nom_valorestruc v5
ON
        (v5.idfila=f.idfila
       and v5.idcampo=CampoFax)
left join
     mod_estructuracomp.nom_valorestruc v3
on
     (v3.idfila=f.idfila
 and v3.idcampo=CampoEmail)
 left join
          mod_nomencladores.nom_dpa  dpa
on
          (dpa.iddpa=h.iddpa)
left join
         mod_nomencladores.nom_dpa  dpa1
on
         (dpa1.iddpa=dpa.idpadre)
/*
inner join

     mod_estructuracomp.nom_dpa dpa,
on
     dpa.iddpa=h.iddpa
inner join
     mod_estructuracomp.nom_dpa dpa1
on

dpa.iddpa=h.iddpa
*/
where
       p.idestructura=id
       and h.idnomeav=EavUnidad
       and h.idorgano=tipo1


/*group by
         h.denominacion,
         h.abreviatura,
         v.valor,
         v1.valor,
         v2.valor,
         v3.valor,
         v4.valor,
         v5.valor*/
order  by
        h.denominacion

loop
return next Result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION mod_estructuracomp.f_rep_relacion_localizunidadesporentidad("NombreNivel" character varying, "Codigo" character varying)
  RETURNS SETOF mod_estructuracomp.td_rep_relacion_localizunidadesporentidad AS
$BODY$
/* Solucion al reporte Relacion
de la localizacion de las unidades por entidad, con inner , falta el join con DPA*/




declare
Result record;
id numeric;
EavNivel numeric;
EavAgrup numeric;
EavEntidad numeric;
EavUnidad numeric;

CampoDomicilio numeric;
CampoLocalidad numeric;
CampoTelefono numeric;
CampoTelefonoDireccion numeric;
CampoFax numeric;
CampoEmail numeric;

BEGIN
id         =mod_estructuracomp."f_rep_buscaridestructura"($1);
EavNivel   =mod_estructuracomp."f_rep_buscarideav"('Nivel 1');
EavAgrup   =mod_estructuracomp."f_rep_buscarideav"('Agrupación');
EavEntidad =mod_estructuracomp."f_rep_buscarideav"('Entidad');
EavUnidad  =mod_estructuracomp."f_rep_buscarideav"('Unidad');


CampoDomicilio=mod_estructuracomp."f_buscaridcampo"('Domicilio',EavUnidad);
CampoLocalidad=mod_estructuracomp."f_buscaridcampo"('localidad',EavUnidad);
CampoTelefono=mod_estructuracomp."f_buscaridcampo"('Telefpizarra',EavUnidad);
CampoTelefonoDireccion=mod_estructuracomp."f_buscaridcampo"('telefdir',EavUnidad);
CampoFax=mod_estructuracomp."f_buscaridcampo"('FAX',EavUnidad);
CampoEmail=mod_estructuracomp."f_buscaridcampo"('email',EavUnidad);

FOR Result IN
SELECT 
       
       h.denominacion,
       h.abreviatura,
       h1.denominacion,
       h1.abreviatura,
       v.valor,
       v1.valor,
      dpa1.denominacion,
      dpa.denominacion,

       v2.valor,
       v4.valor,
       v5.valor,
       v3.valor

FROM

              mod_estructuracomp.dat_estructura p
inner join
              mod_estructuracomp.dat_estructura h
on
              (p.idestructura=id
              AND h.lft>p.lft and h.lft<p.rgt
              and h.idnomeav=EavEntidad)
inner join
              mod_estructuracomp.dat_estructura h1
ON
              (h1.lft>h.lft and h1.lft<h.rgt
              and h1.idnomeav=EavUnidad)
inner join
               mod_estructuracomp.nom_filaestruc f
ON
               (h1.idestructura=f.idfila)
left join
               mod_estructuracomp.nom_valorestruc v
ON
               (v.idfila=f.idfila
                and v.idcampo= CampoDomicilio)
left join

                mod_estructuracomp.nom_valorestruc v1
on

               (v1.idfila=f.idfila
                and v1.idcampo=CampoLocalidad)
left join
               mod_estructuracomp.nom_valorestruc v2
on

               (v2.idfila=f.idfila
                and v2.idcampo=CampoTelefono)
left join
                mod_estructuracomp.nom_valorestruc v4
on

                (v4.idfila=f.idfila
                and v4.idcampo=CampoTelefonoDireccion)
left join
                mod_estructuracomp.nom_valorestruc v5
on
                (v5.idfila=f.idfila
       and v5.idcampo=CampoFax)
left join

                 mod_estructuracomp.nom_valorestruc v3
on
                 (v3.idfila=f.idfila
                 and v3.idcampo=CampoEmail)
left join
          mod_nomencladores.nom_dpa  dpa
on
          (dpa.iddpa=h1.iddpa)
left join
         mod_nomencladores.nom_dpa  dpa1
on
         (dpa1.iddpa=dpa.idpadre)
/*
 
     mod_estructuracomp.nom_dpa dpa,
     mod_estructuracomp.nom_dpa dpa1


where
       p.idestructura=id

       AND h.lft>p.lft and h.lft<p.rgt
       and h.idnomeav=EavEntidad
       AND h1.lft>h.lft and h1.lft<h.rgt
       and h1.idnomeav=EavUnidad
       and h1.idestructura=f.idfila
       and v.idfila=f.idfila
       and v.idcampo= CampoDomicilio
       and v1.idfila=f.idfila
       and v1.idcampo=CampoLocalidad
       and v2.idcampo=CampoTelefono
       and v2.idfila=f.idfila
       and v4.idfila=f.idfila
       and v4.idcampo=CampoTelefonoDireccion
       and v5.idfila=f.idfila
       and v5.idcampo=CampoFax
       and v3.idfila=f.idfila
       and v3.idcampo=CampoEmail
       --and dpa.iddpa=h1.iddpa
       --and dpa.iddpa=h1.iddpa
*//*
group by
         h.denominacion,
         h.abreviatura,
         h1.denominacion,
         h1.abreviatura,
         v.valor,
         v1.valor,
         v2.valor,
         v3.valor,
         v4.valor,
         v5.valor*/
order by
      h1.denominacion



loop
return next Result;
end loop;
END
$BODY$
  LANGUAGE 'plpgsql' ;


-------------------------------------------------------------------------------
----Creación de triggers
--------------------------------------------------------------------------------

	
--fin de la tranzaccion--
commit;