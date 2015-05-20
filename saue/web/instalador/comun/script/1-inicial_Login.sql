-----------------V7.0.0---------------------
/*
--CENTRO DE SOLUCIONES DE GESTIÓN		----
--Departamento de tecnología			----
--2014										----
--SCRIPT de INSTALACION de ESTRUCTURA	----
--										----
--Elaborado por:____René R. Bauta Camejo_____		----
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

CREATE ROLE loginuser CREATEDB CREATEROLE NOINHERIT LOGIN; 
ALTER role loginuser ENCRYPTED PASSWORD 'md5f3a64cabe524a7e1b0e18d8e426ae97b';
            
--termina la tranzaccion--
commit;
