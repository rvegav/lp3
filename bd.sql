PGDMP     !    (            
    {           lp3    15.1    15.1 �   �           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                      false            �           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                      false            �           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                      false            �           1262    16397    lp3    DATABASE     y   CREATE DATABASE lp3 WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'Spanish_Paraguay.1252';
    DROP DATABASE lp3;
                postgres    false                        2615    2200    public    SCHEMA     2   -- *not* creating schema, since initdb creates it
 2   -- *not* dropping schema, since initdb creates it
                postgres    false            �           0    0    SCHEMA public    ACL     Q   REVOKE USAGE ON SCHEMA public FROM PUBLIC;
GRANT ALL ON SCHEMA public TO PUBLIC;
                   postgres    false    5            H           1255    16398 5   calcular_ultimo(character varying, character varying)    FUNCTION       CREATE FUNCTION public.calcular_ultimo(tabla character varying, codigo character varying) RETURNS integer
    LANGUAGE plpgsql
    AS $$
declare ultimo integer;
begin
execute 'select coalesce(max('||codigo||'),0)+1 from '||tabla||'' into ultimo;
return ultimo;
end;
$$;
 Y   DROP FUNCTION public.calcular_ultimo(tabla character varying, codigo character varying);
       public          postgres    false    5            I           1255    16399    convertir_letra(numeric)    FUNCTION     �  CREATE FUNCTION public.convertir_letra(num numeric) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
-- Función que devuelve la cadena de texto en castellano que corresponde a un número.
-- Parámetros: número con 2 decimales, máximo 999999999,99.
 
  
DECLARE
 d VARCHAR[];
 f VARCHAR[];
 
 g VARCHAR[];
 numt VARCHAR;
 txt VARCHAR;
 
 a INTEGER;
 a1 INTEGER;
 a2 INTEGER;
 n INTEGER;
 
 p INTEGER;
 negativo BOOLEAN;
BEGIN
 -- Máximo 999.999.999,99
 
 IF num > 999999999.99 THEN
  RETURN '---';
 
 END IF;
 txt = '';
 d = ARRAY[' un',' dos',' tres',' cuatro',' cinco',' seis',' siete',' ocho',' nueve',' diez',' once',' doce',' trece',' catorce',' quince',
 
  ' dieciseis',' diecisiete',' dieciocho',' diecinueve',' veinte',' veintiun',' veintidos', ' veintitres', ' veinticuatro', ' veinticinco',
 
  ' veintiseis',' veintisiete',' veintiocho',' veintinueve'];
 
 f = ARRAY ['','',' treinta',' cuarenta',' cincuenta',' sesenta',' setenta',' ochenta', ' noventa'];
 
 g= ARRAY [' ciento',' doscientos',' trescientos',' cuatrocientos',' quinientos',' seiscientos',' setecientos',' ochocientos',' novecientos'];
 
 numt = LPAD((num::numeric(12,2))::text,12,'0');
 
 IF strpos(numt,'-') > 0 THEN
 
    negativo = TRUE;
 ELSE
    negativo = FALSE;
 
 END IF;
 numt = TRANSLATE(numt,'-','0');
 
 numt = TRANSLATE(numt,'.,','');
 
 -- Trato 4 grupos: millones, miles, unidades y decimales
 p = 1;
 FOR i IN 1..4 LOOP
 
  IF i < 4 THEN
   n = substring(numt::text FROM p FOR 3);
 
  ELSE
   n = substring(numt::text FROM p FOR 2);
 
  END IF;
  p = p + 3;
  IF i = 4 THEN
 
   IF txt = '' THEN
    txt = ' cero';
 
   END IF;
   IF n > 0 THEN
   -- Empieza con los decimales
 
    txt = txt || ' con';
   END IF;
 
  END IF;
  -- Centenas
  IF n > 99 THEN
 
   a = substring(n::text FROM 1 FOR 1);
 
   a1 = substring(n::text FROM 2 FOR 2);
 
   IF a = 1 THEN
    IF a1 = 0 THEN
 
     txt = txt || ' cien';
    ELSE
     txt = txt || ' ciento';
 
    END IF;
   ELSE
    txt = txt || g[a];
 
   END IF;
  ELSE
   a1 = n;
  END IF;
 
  -- Decenas
  a = a1;
  IF a > 0 THEN
 
   IF a < 30 THEN
    IF a = 21 AND (i = 3 OR i = 4) THEN
 
     txt = txt || ' veintiuno';
    ELSIF n = 1 AND i = 2 THEN
 
     txt = txt;
    ELSIF a = 1 AND (i = 3 OR i = 4)THEN
 
     txt = txt || ' uno';
    ELSE
     txt = txt || d[a];
 
    END IF;
   ELSE
    a1 = substring(a::text FROM 1 FOR 1);
 
    a2 = substring(a::text FROM 2 FOR 1);
 
    IF a2 = 1 AND (i = 3 OR i = 4) THEN
 
      txt = txt || f[a1] || ' y' || ' uno';
 
    ELSE
     IF a2 <> 0 THEN
      txt = txt || f[a1] || ' y' || d[a2];
 
     ELSE
      txt = txt || f[a1];
     END IF;
 
    END IF;
   END IF;
  END IF;
 
  IF n > 0 THEN
   IF i = 1 THEN
 
    IF n = 1 THEN
     txt = txt || ' millón';
 
    ELSE
     txt = txt || ' millones';
    END IF;
 
   ELSIF i = 2 THEN
    txt = txt || ' mil';
 
   END IF; 
  END IF;
 END LOOP;
 
 txt = LTRIM(txt);
 IF negativo = TRUE THEN
 
    txt= '-' || txt;
 END IF;
    RETURN txt;
 
END;
$$;
 3   DROP FUNCTION public.convertir_letra(num numeric);
       public          postgres    false    5            J           1255    16400    ft_actualizar_pedido_venta()    FUNCTION     �  CREATE FUNCTION public.ft_actualizar_pedido_venta() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
declare detalles_ped record;
begin
	if TG_OP = 'INSERT' then
		update pedido_cabventa set estado = 'C'
		where ped_cod = new.ped_cod;
		--recorrer detalle del pedido e insertar en ventas detalle
		--segun el pedido actualizado
		for detalles_ped in select * from detalle_pedventa where ped_cod = new.ped_cod loop
		INSERT INTO detalle_ventas(
		ven_cod, dep_cod, art_cod, ven_cant, ven_precio, exenta, iva_5, iva_10)
		VALUES (new.ven_cod, detalles_ped.dep_cod, detalles_ped.art_cod, 
		detalles_ped.ped_cant, detalles_ped.ped_precio, 0, 0, 0);			
		end loop;
	end if;
	return new;
end;
$$;
 3   DROP FUNCTION public.ft_actualizar_pedido_venta();
       public          postgres    false    5            V           1255    16401 g   sp_articulo(integer, integer, character varying, integer, character varying, integer, integer, integer)    FUNCTION     o  CREATE FUNCTION public.sp_articulo(ban integer, vart_cod integer, vart_codbarra character varying, vmar_cod integer, vart_descri character varying, vart_precioc integer, vart_preciov integer, vtipo_cod integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
		INSERT INTO articulo(art_cod, art_codbarra, mar_cod, 
		art_descri, art_precioc, art_preciov, tipo_cod)
		VALUES (calcular_ultimo('articulo','art_cod'), vart_codbarra, vmar_cod, trim(upper(vart_descri)), 
		vart_precioc, vart_preciov, vtipo_cod);	
		mensaje = 'Se guardo correctamente el articulo*articulo_index';	
	end if;
	if ban = 2 then
		/* colocar las respectivas sentencias para modificar*/
		update articulo 
		set art_codbarra = vart_codbarra,
		mar_cod = vmar_cod,
		art_descri = vart_descri,
		art_precioc = vart_precioc,
		art_preciov = vart_preciov,
		tipo_cod = vtipo_cod
		where art_cod = vart_cod;
		mensaje = 'Se ha actualizado correctamente el articulo';
	end if;
	if ban = 3 then
		delete from articulo where art_cod = vart_cod;
		mensaje = 'Se ha borrado correctamente el cliente';
	end if;
	if ban = 4 then --agregar marca
		insert into marca values(calcular_ultimo('marca','mar_cod'),
		trim(upper(vart_descri)));
	mensaje = 'Se guardo correctamente la marca*articulo_add';
	end if;
	if ban = 5 then --tipo impuesto
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_articulo(ban integer, vart_cod integer, vart_codbarra character varying, vmar_cod integer, vart_descri character varying, vart_precioc integer, vart_preciov integer, vtipo_cod integer);
       public          postgres    false    5            W           1255    16402 -   sp_cargo(integer, integer, character varying)    FUNCTION     �  CREATE FUNCTION public.sp_cargo(ban integer, vcar_cod integer, vcar_descri character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
	insert into cargo(car_cod,car_descri)
	values (calcular_ultimo('cargo','car_cod'),trim(upper(vcar_descri)));
	mensaje = 'Se guardo correctamente el cargo <strong>' ||trim(upper(vcar_descri))||'</strong>';
	end if;
	return mensaje;
end;
$$;
 ]   DROP FUNCTION public.sp_cargo(ban integer, vcar_cod integer, vcar_descri character varying);
       public          postgres    false    5            X           1255    16403 r   sp_clientes(integer, integer, integer, character varying, character varying, character varying, character varying)    FUNCTION     �  CREATE FUNCTION public.sp_clientes(ban integer, vcli_cod integer, vcli_ci integer, vcli_nombre character varying, vcli_apellido character varying, vcli_telefono character varying, vcli_direcc character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
declare ultimo integer;
begin
case ban
	when 1 then 
	select coalesce(max(cli_cod),0)+1 into ultimo from clientes;
	
	perform * from clientes where cli_ci = vcli_ci;
	if found then
		
		mensaje = 'Ya existe el cliente con CI '||vcli_ci;
	else
	insert into clientes(cli_cod,cli_ci,cli_nombre,cli_apellido,cli_telefono,cli_direcc)
	values (vcli_cod,vcli_ci,trim(upper(vcli_nombre)),trim(upper(vcli_apellido)),vcli_telefono,trim(upper(vcli_direcc)));
	mensaje = 'Se guardo correctamente el cliente';
	end if;
	when 2 then
	update clientes set cli_ci = vcli_ci,cli_nombre=trim(upper(vcli_nombre)),
	cli_apellido = trim(upper(vcli_apellido)),
	cli_telefono = 	vcli_telefono,
	cli_direcc = trim(upper(vcli_direcc))
	where cli_cod = vcli_cod;
	mensaje = 'Se ha actualizado correctamente el cliente';
	when 3 then
	delete from clientes where cli_cod = vcli_cod;
	mensaje = 'Se ha borrado correctamente el cliente';
end case;
return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_clientes(ban integer, vcli_cod integer, vcli_ci integer, vcli_nombre character varying, vcli_apellido character varying, vcli_telefono character varying, vcli_direcc character varying);
       public          postgres    false    5            e           1255    16404 e   sp_compras(integer, integer, integer, integer, character varying, integer, integer, integer, integer)    FUNCTION        CREATE FUNCTION public.sp_compras(ban integer, vcom_cod integer, vemp_cod integer, vprv_cod integer, vtipo_compra character varying, vcan_cuota integer, vcom_plazo integer, vid_sucursal integer, vped_cod integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare ultimo integer;
declare mensaje varchar;
declare presupuesto integer;
begin
	if ban = 1 then
	perform * from compras where com_estado = 'P';
	if found then
		mensaje= 'No se puede agregar una compra hasta que se confirme las compras pendientes*compras_index.php';
	else
		INSERT INTO compras(com_cod, emp_cod, prv_cod, com_fecha, tipo_compra, can_cuota, com_plazo, com_total,
		 com_estado, id_sucursal)
		VALUES (calcular_ultimo('compras','com_cod'), vemp_cod,vprv_cod,current_date, vtipo_compra, vcan_cuota, vcom_plazo, 0, 
		'P', vid_sucursal)RETURNING com_cod into ultimo;
		if vped_cod > 0 then
			insert into pedido_compra values(vped_cod,ultimo);
		end if;		
		mensaje = 'Se agrego correctamente la compra*compras_det.php?vcom_cod='||ultimo;
		insert into detalle_compras(art_cod,com_cant,com_precio, com_cod) (SELECT dp.art_cod, dp.ped_cant, dp.ped_precio_presup, pc.com_cod
			  FROM  detalle_pedcompra dp join pedido_compra pc on pc.ped_cod = dp.ped_cod where dp.ped_cod = vped_cod );
	end if;

	end if;
	select p.pres_cod into presupuesto  from presupuestos p join pedido_compra pc on pc.ped_cod = p.pres_ped_cod where pc.com_cod = vcom_cod and p.pres_estado = 'A';
	if ban = 2 then --confirmar
		update compras set com_estado = 'C'
		where com_cod = vcom_cod;
	mensaje = 'Se confirmo correctamente la compra*compras_index.php';		
	end if;	
	if ban = 3 then --anular
		update compras set com_estado = 'A'
		where com_cod = vcom_cod;
	mensaje = 'Se anulo correctamente la compra*compras_index.php';		
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_compras(ban integer, vcom_cod integer, vemp_cod integer, vprv_cod integer, vtipo_compra character varying, vcan_cuota integer, vcom_plazo integer, vid_sucursal integer, vped_cod integer);
       public          postgres    false    5            Y           1255    16405 H   sp_detalle_compras(integer, integer, integer, integer, integer, integer)    FUNCTION       CREATE FUNCTION public.sp_detalle_compras(ban integer, vcom_cod integer, vdep_cod integer, vart_cod integer, vcom_cant integer, vcom_precio integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
declare varticulo record;
begin
	--obtiene los datos del articulo
	select * into varticulo from articulo where art_cod = vart_cod;
	
	if ban = 1 then --agregar detalle
	perform * from detalle_compras where com_cod = vcom_cod and art_cod = vart_cod and dep_cod = vdep_cod;
	if not found then
	INSERT INTO detalle_compras(com_cod, dep_cod, art_cod, com_cant, com_precio, exenta, iva_5, iva_10)
	    VALUES (vcom_cod, vdep_cod, vart_cod, vcom_cant, vcom_precio,(case varticulo.tipo_cod when 1 then vcom_precio else 0 end),
	    (case varticulo.tipo_cod when 2 then vcom_precio else 0 end),(case varticulo.tipo_cod when 3 then vcom_precio else 0 end));		
	else
		update detalle_compras set com_cant = vcom_cant, com_precio = vcom_precio,
		exenta = (case varticulo.tipo_cod when 1 then vcom_precio else 0 end),
		iva_5 = (case varticulo.tipo_cod when 2 then vcom_precio else 0 end),
		iva_10 = (case varticulo.tipo_cod when 3 then vcom_precio else 0 end)
		where com_cod = vcom_cod and art_cod = vart_cod and dep_cod = vdep_cod;
	end if;
	   mensaje = 'Se agrego correctamente el articulo a la compra';
	end if;
	if ban = 2 then --actualizacion
		update detalle_compras set com_cant = vcom_cant, com_precio = vcom_precio,
		exenta = (case varticulo.tipo_cod when 1 then vcom_precio else 0 end),
		iva_5 = (case varticulo.tipo_cod when 2 then vcom_precio else 0 end),
		iva_10 = (case varticulo.tipo_cod when 3 then vcom_precio else 0 end)		
		where com_cod = vcom_cod and art_cod = vart_cod and dep_cod = vdep_cod;	
		mensaje = 'Se actualizo correctamente el articulo a la compra';
	end if;
	if ban = 3 then --quitar articulo
		delete from detalle_compras
		where com_cod = vcom_cod and art_cod = vart_cod and dep_cod = vdep_cod;	
		mensaje = 'Se borro correctamente el articulo a la compra';
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_detalle_compras(ban integer, vcom_cod integer, vdep_cod integer, vart_cod integer, vcom_cant integer, vcom_precio integer);
       public          postgres    false    5            c           1255    16406 J   sp_detalle_pedcompra(integer, integer, integer, integer, integer, integer)    FUNCTION     �  CREATE FUNCTION public.sp_detalle_pedcompra(ban integer, vped_cod integer, vdep_cod integer, vart_cod integer, vped_cant integer, vped_precio integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
			INSERT INTO detalle_pedcompra(
			    ped_cod, dep_cod, art_cod, ped_cant, ped_precio)
		    VALUES (vped_cod, vdep_cod, vart_cod, vped_cant, vped_precio);
		    mensaje = 'Se agrego correctamente el artiulo al pedido';
	end if;
	if ban = 2 then
		update detalle_pedcompra set ped_cant = vped_cant,
		ped_precio = vped_precio
		where ped_cod = vped_cod and art_cod = vart_cod
		and dep_cod = vdep_cod;
		mensaje = 'Se modifico correctamente el artiulo al pedido';
	end if;
	if ban = 3 then
		delete from detalle_pedcompra 
		where ped_cod = vped_cod and art_cod = vart_cod
		and dep_cod = vdep_cod;
		mensaje = 'Se elimino correctamente el artiulo al pedido';
	end if;
	if ban = 4 then
		update detalle_pedcompra set
		ped_precio_presup = vped_precio
		where ped_cod = vped_cod and art_cod = vart_cod
		and dep_cod = vdep_cod;
		mensaje = 'Se modifico correctamente el artiulo al pedido';
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_detalle_pedcompra(ban integer, vped_cod integer, vdep_cod integer, vart_cod integer, vped_cant integer, vped_precio integer);
       public          postgres    false    5            Z           1255    16407 I   sp_detalle_pedventa(integer, integer, integer, integer, integer, integer)    FUNCTION     �  CREATE FUNCTION public.sp_detalle_pedventa(ban integer, vped_cod integer, vdep_cod integer, vart_cod integer, vped_cant integer, vped_precio integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
			INSERT INTO detalle_pedventa(
			    ped_cod, dep_cod, art_cod, ped_cant, ped_precio)
		    VALUES (vped_cod, vdep_cod, vart_cod, vped_cant, vped_precio);
		    mensaje = 'Se agrego correctamente el artiulo al pedido';
	end if;
	if ban = 2 then
		update detalle_pedventa set ped_cant = vped_cant,
		ped_precio = vped_precio
		where ped_cod = vped_cod and art_cod = vart_cod
		and dep_cod = vdep_cod;
		mensaje = 'Se modifico correctamente el artiulo al pedido';
	end if;
	if ban = 3 then
		delete from detalle_pedventa 
		where ped_cod = vped_cod and art_cod = vart_cod
		and dep_cod = vdep_cod;
		mensaje = 'Se elimino correctamente el artiulo al pedido';
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_detalle_pedventa(ban integer, vped_cod integer, vdep_cod integer, vart_cod integer, vped_cant integer, vped_precio integer);
       public          postgres    false    5            [           1255    16408 G   sp_detalle_ventas(integer, integer, integer, integer, integer, integer)    FUNCTION     �  CREATE FUNCTION public.sp_detalle_ventas(ban integer, vven_cod integer, vdep_cod integer, vart_cod integer, vven_cant integer, vven_precio integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
declare varticulo record;
begin
	--obtiene los datos del articulo
	select * into varticulo from articulo where art_cod = vart_cod;
	
	if ban = 1 then --agregar detalle
	perform * from detalle_ventas where ven_cod = vven_cod and art_cod = vart_cod and dep_cod = vdep_cod;
	if not found then
	INSERT INTO detalle_ventas(ven_cod, dep_cod, art_cod, ven_cant, ven_precio, exenta, iva_5, iva_10)
	    VALUES (vven_cod, vdep_cod, vart_cod, vven_cant, vven_precio,(case varticulo.tipo_cod when 1 then vven_precio else 0 end),
	    (case varticulo.tipo_cod when 2 then vven_precio else 0 end),(case varticulo.tipo_cod when 3 then vven_precio else 0 end));		
	else
		update detalle_ventas set ven_cant = vven_cant, ven_precio = vven_precio,
		exenta = (case varticulo.tipo_cod when 1 then vven_precio else 0 end),
		iva_5 = (case varticulo.tipo_cod when 2 then vven_precio else 0 end),
		iva_10 = (case varticulo.tipo_cod when 3 then vven_precio else 0 end)
		where ven_cod = vven_cod and art_cod = vart_cod and dep_cod = vdep_cod;
	end if;
	   mensaje = 'Se agrego correctamente el articulo a la venta';
	end if;
	if ban = 2 then --actualizacion
		update detalle_ventas set ven_cant = vven_cant, ven_precio = vven_precio,
		exenta = (case varticulo.tipo_cod when 1 then vven_precio else 0 end),
		iva_5 = (case varticulo.tipo_cod when 2 then vven_precio else 0 end),
		iva_10 = (case varticulo.tipo_cod when 3 then vven_precio else 0 end)		
		where ven_cod = vven_cod and art_cod = vart_cod and dep_cod = vdep_cod;	
		mensaje = 'Se actualizo correctamente el articulo a la venta';
	end if;
	if ban = 3 then --quitar articulo
		delete from detalle_ventas
		where ven_cod = vven_cod and art_cod = vart_cod and dep_cod = vdep_cod;	
		mensaje = 'Se borro correctamente el articulo a la venta';
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_detalle_ventas(ban integer, vven_cod integer, vdep_cod integer, vart_cod integer, vven_cant integer, vven_precio integer);
       public          postgres    false    5            \           1255    16409 -   sp_marca(integer, integer, character varying)    FUNCTION     �  CREATE FUNCTION public.sp_marca(ban integer, vmar_cod integer, vmar_descri character varying) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
		insert into marca(mar_cod,mar_descri)
		values ((select coalesce(max(mar_cod),0)+1 from marca),upper (vmar_descri));
		mensaje = 'Se guardo correctamente la marca';
	end if;
	if ban = 2 then
	update marca set mar_descri = trim(upper(vmar_descri))
	where mar_cod = vmar_cod;
	mensaje = 'Se actualizo correctamente la marca';
	end if;
	if ban = 3 then
	delete from  marca
	where mar_cod = vmar_cod;
	mensaje = 'Se elimino correctamente la marca';
	end if;
	return mensaje;
end;
$$;
 ]   DROP FUNCTION public.sp_marca(ban integer, vmar_cod integer, vmar_descri character varying);
       public          postgres    false    5            ]           1255    16410 :   sp_pedcompras(integer, integer, integer, integer, integer)    FUNCTION     |  CREATE FUNCTION public.sp_pedcompras(ban integer, vped_cod integer, vemp_cod integer, vprv_cod integer, vid_sucursal integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
		INSERT INTO pedido_cabcompra(
			    ped_cod, ped_fecha, emp_cod, prv_cod, estado, id_sucursal)
		    VALUES (calcular_ultimo('pedido_cabcompra','ped_cod'),
		     current_date, vemp_cod, vprv_cod, 'P', vid_sucursal);		
		     mensaje = 'Se agrego correctamente el pedido';
	end if;
	if ban = 2 then
		update pedido_cabcompra set prv_cod = vprv_cod
		where ped_cod = vped_cod;
		mensaje = 'Se modifico correctamente el pedido';
	end if;
	if ban = 3 then
		update pedido_cabcompra set estado = 'A'
		where ped_cod = vped_cod;
		mensaje = 'Se anulo correctamente el pedido';
	end if;	
	if ban = 4 then --AGREGAR NUEVO CLIENTE
	end if;
	return mensaje;
end;
$$;
 }   DROP FUNCTION public.sp_pedcompras(ban integer, vped_cod integer, vemp_cod integer, vprv_cod integer, vid_sucursal integer);
       public          postgres    false    5            ^           1255    16411 9   sp_pedventas(integer, integer, integer, integer, integer)    FUNCTION       CREATE FUNCTION public.sp_pedventas(ban integer, vped_cod integer, vemp_cod integer, vcli_cod integer, vid_sucursal integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
		INSERT INTO pedido_cabventa(
			    ped_cod, ped_fecha, emp_cod, cli_cod, estado, id_sucursal)
		    VALUES (calcular_ultimo('pedido_cabventa','ped_cod'),
		     current_date, vemp_cod, vcli_cod, 'P', vid_sucursal);		
		     mensaje = 'Se agrego correctamente el pedido';
	end if;
	if ban = 2 then
		update pedido_cabventa set cli_cod = vcli_cod
		where ped_cod = vped_cod;
		mensaje = 'Se modifico correctamente el pedido';
	end if;
	if ban = 3 then
		update pedido_cabventa set estado = 'A'
		where ped_cod = vped_cod;
		mensaje = 'Se anulo correctamente el pedido';
	end if;	
	if ban = 4 then --AGREGAR NUEVO CLIENTE
	end if;
	if ban = 5 then
		update pedido_cabventa set estado = 'C'
		where ped_cod = vped_cod;
		mensaje = 'Se Confirmo correctamente el pedido';
	end if;	
	return mensaje;
end;
$$;
 |   DROP FUNCTION public.sp_pedventas(ban integer, vped_cod integer, vemp_cod integer, vcli_cod integer, vid_sucursal integer);
       public          postgres    false    5            _           1255    16412 5   sp_stock(integer, integer, integer, integer, integer)    FUNCTION        CREATE FUNCTION public.sp_stock(ban integer, vdep_cod integer, vart_cod integer, vcant_min_cod integer, vcan_stock integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
		INSERT INTO stock (dep_cod,art_cod,cant_minima,stoc_cant)values(vdep_cod,vart_cod,vcant_min_cod,vcan_stock);		
		mensaje = 'Se agrego correctamente el pedido';
	end if;
	if ban = 2 then
		update stock set stoc_cant = vcan_stock
		where art_cod = vart_cod and dep_cod = vdep_cod;
		mensaje = 'Se modifico correctamente el pedido';
	end if;
	if ban = 3 then --AGREGAR NUEVO CLIENTE
		delete from stock
		where art_cod = vart_cod and dep_cod = vdep_cod;
		mensaje = 'Se modifico correctamente el pedido';
	end if;
	return mensaje;
end;
$$;
 {   DROP FUNCTION public.sp_stock(ban integer, vdep_cod integer, vart_cod integer, vcant_min_cod integer, vcan_stock integer);
       public          postgres    false    5            `           1255    16413 =   sp_tipoimpuesto(integer, integer, character varying, integer)    FUNCTION     I  CREATE FUNCTION public.sp_tipoimpuesto(ban integer, vtipo_cod integer, vtipo_descri character varying, vtipo_porcen integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then
		insert into tipo_impuesto(tipo_cod,tipo_descri,tipo_porcen)
		values(calcular_ultimo('tipo_impuesto','tipo_cod'),
		trim(upper(vtipo_descri)),vtipo_porcen);
		mensaje = 'Se guardo correctamente el tipo de impuesto';
	end if;
	if ban = 2 then
		update tipo_impuesto set tipo_descri = trim(upper(vtipo_descri)),
		tipo_porcen = vtipo_porcen
		where tipo_cod = vtipo_cod;
		mensaje = 'Se modifico correctamente el tipo de impuesto';
	end if;
	if ban = 3 then
		delete from tipo_impuesto
		where tipo_cod = vtipo_cod;
		mensaje = 'Se elimino correctamente el tipo de impuesto';
	end if;
	return mensaje;
end;
$$;
 |   DROP FUNCTION public.sp_tipoimpuesto(ban integer, vtipo_cod integer, vtipo_descri character varying, vtipo_porcen integer);
       public          postgres    false    5            a           1255    16414 ]   sp_usuario(integer, integer, character varying, character varying, integer, integer, integer)    FUNCTION     �  CREATE FUNCTION public.sp_usuario(ban integer, vusu_cod integer, vusu_nick character varying, vusu_clave character varying, vemp_cod integer, vgru_cod integer, vid_sucursal integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare mensaje varchar;
begin
	if ban = 1 then 
		perform * from usuarios where upper(usu_nick) = upper(vusu_nick);
	if found then
		mensaje = 'Ya existe un usuario con el nick '||vusu_nick||'*usuarios_index';
	else
		INSERT INTO usuarios(
			    usu_cod, usu_nick, usu_clave, emp_cod, gru_cod, id_sucursal)
		    VALUES (calcular_ultimo('usuarios','usu_cod'),vusu_nick, md5(vusu_clave),
		    vemp_cod, vgru_cod, vid_sucursal);
		    mensaje = 'Se creo correctamente el usuario*usuarios_index';
	end if;
	end if;
	if ban = 4 then 
		perform * from usuarios where upper(usu_nick) = upper(vusu_nick);
	if found then
		mensaje = 'Ya existe un usuario con el nick '||vusu_nick||'*usuarios_index';
	else
			INSERT INTO usuarios(
			    usu_cod, usu_nick, usu_clave, emp_cod, gru_cod, id_sucursal)
		    VALUES (calcular_ultimo('usuarios','usu_cod'),vusu_nick, md5(vusu_clave),
		    vemp_cod, vgru_cod, vid_sucursal);
		    mensaje = 'Se creo correctamente el usuario*empleado_index';
	end if;
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_usuario(ban integer, vusu_cod integer, vusu_nick character varying, vusu_clave character varying, vemp_cod integer, vgru_cod integer, vid_sucursal integer);
       public          postgres    false    5            d           1255    16415 d   sp_ventas(integer, integer, integer, integer, character varying, integer, integer, integer, integer)    FUNCTION     �  CREATE FUNCTION public.sp_ventas(ban integer, vven_cod integer, vemp_cod integer, vcli_cod integer, vtipo_venta character varying, vcan_cuota integer, vven_plazo integer, vid_sucursal integer, vped_cod integer) RETURNS character varying
    LANGUAGE plpgsql
    AS $$
declare ultimo integer;
declare mensaje varchar;
begin
	if ban = 1 then
	perform * from ventas where ven_estado = 'P';
	if found then
		mensaje= 'No se puede agregar una venta hasta que se confirme las ventas pendientes*ventas_index.php';
	else
		INSERT INTO ventas(ven_cod, emp_cod, cli_cod, ven_fecha, tipo_venta, can_cuota, ven_plazo, ven_total,
		 ven_estado, id_sucursal)
		VALUES (calcular_ultimo('ventas','ven_cod'), vemp_cod,vcli_cod,current_date, vtipo_venta, vcan_cuota, vven_plazo, 0, 
		'P', vid_sucursal)RETURNING ven_cod into ultimo;
		if vped_cod > 0 then
			insert into pedido_venta values(vped_cod,ultimo);
		end if;		
		mensaje = 'Se agrego correctamente la venta*ventas_det.php?vven_cod='||ultimo;
	end if;

	end if;
	if ban = 2 then --confirmar
		update ventas set ven_estado = 'C'
		where ven_cod = vven_cod;
		update ctas_a_cobrar set estado_cuota = 'A'
		where ven_cod = (select ven_cod from ventas v where v.tipo_venta = 'CONTADO' and v.ven_cod = vven_cod );
	mensaje = 'Se confirmo correctamente la venta*ventas_index.php';		
	end if;	
	if ban = 3 then --anular
		update ventas set ven_estado = 'A'
		where ven_cod = vven_cod;
	mensaje = 'Se anulo correctamente la venta*ventas_index.php';		
	end if;
	return mensaje;
end;
$$;
 �   DROP FUNCTION public.sp_ventas(ban integer, vven_cod integer, vemp_cod integer, vcli_cod integer, vtipo_venta character varying, vcan_cuota integer, vven_plazo integer, vid_sucursal integer, vped_cod integer);
       public          postgres    false    5            b           1255    16416    sp_verificar_articulo()    FUNCTION     [  CREATE FUNCTION public.sp_verificar_articulo() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
begin
	perform * from stock where dep_cod = new.dep_cod
	and art_cod = new.art_cod;
	if not found then
	INSERT INTO stock(
            dep_cod, art_cod, cant_minima, stoc_cant)
    VALUES (new.dep_cod, new.art_cod, 0, 0);
	end if;
	return new;
end;
$$;
 .   DROP FUNCTION public.sp_verificar_articulo();
       public          postgres    false    5            �            1259    16417    aguinaldo_seq    SEQUENCE     v   CREATE SEQUENCE public.aguinaldo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.aguinaldo_seq;
       public          postgres    false    5            �            1259    16422    apertura_cierre    TABLE     �  CREATE TABLE public.apertura_cierre (
    nro_aper integer NOT NULL,
    caj_cod integer NOT NULL,
    id_sucursal integer NOT NULL,
    aper_fecha date NOT NULL,
    usu_cod integer NOT NULL,
    aper_cierre date,
    monto_aper integer NOT NULL,
    total_efectivo integer,
    total_cheque integer,
    total_credito integer,
    estado character varying DEFAULT 'I'::character varying,
    cod_timbrado integer
);
 #   DROP TABLE public.apertura_cierre;
       public         heap    postgres    false    5            �            1259    16428    articulo    TABLE       CREATE TABLE public.articulo (
    art_cod integer NOT NULL,
    art_codbarra character varying(15),
    mar_cod integer,
    art_descri character varying(100),
    art_precioc integer,
    art_preciov integer NOT NULL,
    tipo_cod integer NOT NULL,
    art_tipo_fab integer
);
    DROP TABLE public.articulo;
       public         heap    postgres    false    5            �            1259    16431    asistencia_seq    SEQUENCE     w   CREATE SEQUENCE public.asistencia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.asistencia_seq;
       public          postgres    false    5            �            1259    16443 
   barrio_seq    SEQUENCE     s   CREATE SEQUENCE public.barrio_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 !   DROP SEQUENCE public.barrio_seq;
       public          postgres    false    5            �            1259    16449    bonificacion_familiar_seq    SEQUENCE     �   CREATE SEQUENCE public.bonificacion_familiar_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 0   DROP SEQUENCE public.bonificacion_familiar_seq;
       public          postgres    false    5            �            1259    16454    bonificaciones_seq    SEQUENCE     {   CREATE SEQUENCE public.bonificaciones_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.bonificaciones_seq;
       public          postgres    false    5            �            1259    16460    caja    TABLE     �   CREATE TABLE public.caja (
    caj_cod integer NOT NULL,
    caj_descri character varying NOT NULL,
    id_sucursal integer NOT NULL
);
    DROP TABLE public.caja;
       public         heap    postgres    false    5            �            1259    16468    cargo_rrhh_seq    SEQUENCE     w   CREATE SEQUENCE public.cargo_rrhh_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.cargo_rrhh_seq;
       public          postgres    false    5            �            1259    16474 
   ciudad_seq    SEQUENCE     s   CREATE SEQUENCE public.ciudad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 !   DROP SEQUENCE public.ciudad_seq;
       public          postgres    false    5            �            1259    16479    clientes    TABLE     �   CREATE TABLE public.clientes (
    cli_cod integer NOT NULL,
    cli_ci integer,
    cli_nombre character varying(100),
    cli_apellido character varying(100),
    cli_telefono character varying(15),
    cli_direcc character varying(100)
);
    DROP TABLE public.clientes;
       public         heap    postgres    false    5            9           1259    32862    composion_articulos    TABLE     �   CREATE TABLE public.composion_articulos (
    coar_id integer NOT NULL,
    coar_mapr_id integer,
    coar_art_id integer,
    coar_cant_requerida numeric
);
 '   DROP TABLE public.composion_articulos;
       public         heap    postgres    false    5            �            1259    16482    compras    TABLE     �  CREATE TABLE public.compras (
    com_cod integer NOT NULL,
    emp_cod integer NOT NULL,
    prv_cod integer NOT NULL,
    com_fecha date NOT NULL,
    tipo_compra character varying(10) NOT NULL,
    can_cuota integer NOT NULL,
    com_plazo integer NOT NULL,
    com_total integer NOT NULL,
    com_estado character varying(1) NOT NULL,
    id_sucursal integer NOT NULL,
    com_estado_pago character varying DEFAULT 'I'::character varying
);
    DROP TABLE public.compras;
       public         heap    postgres    false    5            �            1259    16485    contrato_seq    SEQUENCE     u   CREATE SEQUENCE public.contrato_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.contrato_seq;
       public          postgres    false    5            �            1259    16492    control_calidad    TABLE     
  CREATE TABLE public.control_calidad (
    coca_id integer NOT NULL,
    coca_pepr_id integer,
    coca_inspeccion_area_acabado integer,
    coca_funciones_alcance integer,
    coca_inspeccion_total integer,
    coca_calificacion integer,
    coca_prod_id integer
);
 #   DROP TABLE public.control_calidad;
       public         heap    postgres    false    5            �            1259    16495    control_calidad_coca_id_seq    SEQUENCE     �   CREATE SEQUENCE public.control_calidad_coca_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE public.control_calidad_coca_id_seq;
       public          postgres    false    5    227            �           0    0    control_calidad_coca_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE public.control_calidad_coca_id_seq OWNED BY public.control_calidad.coca_id;
          public          postgres    false    228            =           1259    32911    control_produccion    TABLE     0  CREATE TABLE public.control_produccion (
    copr_id integer NOT NULL,
    copr_prod_id integer,
    copr_etpr_id integer,
    copr_fecha date,
    copr_estado character varying,
    copr_empl_id integer,
    copr_observacion character varying,
    copr_canti_producida integer,
    copr_item integer
);
 &   DROP TABLE public.control_produccion;
       public         heap    postgres    false    5            A           1259    32975    costo_produccion    TABLE     �   CREATE TABLE public.costo_produccion (
    cospr_id integer NOT NULL,
    cospr_prod_id integer,
    cospr_monto_produccion numeric,
    cospr_monto_mano_obra numeric,
    cospr_fecha date
);
 $   DROP TABLE public.costo_produccion;
       public         heap    postgres    false    5            �            1259    16496    ctas_a_cobrar    TABLE     �   CREATE TABLE public.ctas_a_cobrar (
    nro_cuota integer NOT NULL,
    ven_cod integer NOT NULL,
    monto_cuota integer,
    saldo_cuota integer,
    fecha_venc date,
    estado_cuota character varying(1) NOT NULL,
    ctco_id integer NOT NULL
);
 !   DROP TABLE public.ctas_a_cobrar;
       public         heap    postgres    false    5            F           1259    49437    ctas_a_cobrar_ctco_seq    SEQUENCE     �   CREATE SEQUENCE public.ctas_a_cobrar_ctco_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 -   DROP SEQUENCE public.ctas_a_cobrar_ctco_seq;
       public          postgres    false    5    229            �           0    0    ctas_a_cobrar_ctco_seq    SEQUENCE OWNED BY     T   ALTER SEQUENCE public.ctas_a_cobrar_ctco_seq OWNED BY public.ctas_a_cobrar.ctco_id;
          public          postgres    false    326            �            1259    16499    ctas_a_pagar    TABLE     T  CREATE TABLE public.ctas_a_pagar (
    nro_cuota integer NOT NULL,
    com_cod integer NOT NULL,
    monto_cuota integer NOT NULL,
    saldo_cuota integer NOT NULL,
    fecha_venc date,
    estado_cuota character varying(1) NOT NULL,
    ctpa_id integer NOT NULL,
    ctpa_forma_pago "char" DEFAULT 'E'::"char",
    ctpa_fecha_pago date
);
     DROP TABLE public.ctas_a_pagar;
       public         heap    postgres    false    5            E           1259    49420    ctas_a_pagar_ctpa_id_seq    SEQUENCE     �   CREATE SEQUENCE public.ctas_a_pagar_ctpa_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.ctas_a_pagar_ctpa_id_seq;
       public          postgres    false    5    230            �           0    0    ctas_a_pagar_ctpa_id_seq    SEQUENCE OWNED BY     U   ALTER SEQUENCE public.ctas_a_pagar_ctpa_id_seq OWNED BY public.ctas_a_pagar.ctpa_id;
          public          postgres    false    325            �            1259    16502    curriculum_seq    SEQUENCE     w   CREATE SEQUENCE public.curriculum_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.curriculum_seq;
       public          postgres    false    5            �            1259    16515    departamento_seccion_seq    SEQUENCE     �   CREATE SEQUENCE public.departamento_seccion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 /   DROP SEQUENCE public.departamento_seccion_seq;
       public          postgres    false    5            �            1259    16510    departamento_seq    SEQUENCE     y   CREATE SEQUENCE public.departamento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.departamento_seq;
       public          postgres    false    5            �            1259    16521    deposito    TABLE     �   CREATE TABLE public.deposito (
    dep_cod integer NOT NULL,
    dep_descri character varying(60),
    id_sucursal integer NOT NULL
);
    DROP TABLE public.deposito;
       public         heap    postgres    false    5            �            1259    16527    descuento_rrhh_seq    SEQUENCE     {   CREATE SEQUENCE public.descuento_rrhh_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.descuento_rrhh_seq;
       public          postgres    false    5            �            1259    16532    detalle_cheques    TABLE     ;  CREATE TABLE public.detalle_cheques (
    ban_cod integer NOT NULL,
    form_cod character varying NOT NULL,
    ven_cod integer NOT NULL,
    tipo_cheque character varying NOT NULL,
    monto_cheque integer NOT NULL,
    fecha_venc date NOT NULL,
    fecha_cheque date NOT NULL,
    nro_cheque integer NOT NULL
);
 #   DROP TABLE public.detalle_cheques;
       public         heap    postgres    false    5            �            1259    16537    detalle_cobros    TABLE     $  CREATE TABLE public.detalle_cobros (
    nro_cuota integer NOT NULL,
    ven_cod integer NOT NULL,
    monto_cobrado integer NOT NULL,
    deco_metodo "char" DEFAULT 'E'::"char",
    deco_id integer NOT NULL,
    deco_ctco_id integer,
    deco_fecha_pago date,
    deco_nro_tarjeta "char"
);
 "   DROP TABLE public.detalle_cobros;
       public         heap    postgres    false    5            G           1259    49447    detalle_cobros_deco_id_seq    SEQUENCE     �   CREATE SEQUENCE public.detalle_cobros_deco_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 1   DROP SEQUENCE public.detalle_cobros_deco_id_seq;
       public          postgres    false    5    237            �           0    0    detalle_cobros_deco_id_seq    SEQUENCE OWNED BY     Y   ALTER SEQUENCE public.detalle_cobros_deco_id_seq OWNED BY public.detalle_cobros.deco_id;
          public          postgres    false    327            �            1259    16540    detalle_compras    TABLE       CREATE TABLE public.detalle_compras (
    dep_cod integer NOT NULL,
    art_cod integer NOT NULL,
    com_cod integer NOT NULL,
    com_cant integer NOT NULL,
    com_precio integer NOT NULL,
    exenta integer,
    iva_5 integer,
    iva_10 integer,
    deco_mapr_id integer
);
 #   DROP TABLE public.detalle_compras;
       public         heap    postgres    false    5            7           1259    24699    detalle_compras_dep_cod_seq    SEQUENCE     �   ALTER TABLE public.detalle_compras ALTER COLUMN dep_cod ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.detalle_compras_dep_cod_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          postgres    false    5    238            �            1259    16543    detalle_fabricacion    TABLE     �   CREATE TABLE public.detalle_fabricacion (
    depr_id integer NOT NULL,
    depr_cod_producto integer NOT NULL,
    depr_cantidad integer NOT NULL,
    depr_precio_total integer NOT NULL,
    depr_prod_id integer
);
 '   DROP TABLE public.detalle_fabricacion;
       public         heap    postgres    false    5            �            1259    16546    detalle_fabricacion_defa_id_seq    SEQUENCE     �   CREATE SEQUENCE public.detalle_fabricacion_defa_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 6   DROP SEQUENCE public.detalle_fabricacion_defa_id_seq;
       public          postgres    false    5    239            �           0    0    detalle_fabricacion_defa_id_seq    SEQUENCE OWNED BY     c   ALTER SEQUENCE public.detalle_fabricacion_defa_id_seq OWNED BY public.detalle_fabricacion.depr_id;
          public          postgres    false    240            2           1259    24631    detalle_factura_compra    TABLE     �   CREATE TABLE public.detalle_factura_compra (
    defc_cod integer NOT NULL,
    defc_com_cod integer,
    defc_faco_cod integer,
    defc_art_cod integer,
    defc_cant integer,
    defc_precio_compra integer
);
 *   DROP TABLE public.detalle_factura_compra;
       public         heap    postgres    false    5            3           1259    24653 #   detalle_factura_compra_defc_cod_seq    SEQUENCE     �   ALTER TABLE public.detalle_factura_compra ALTER COLUMN defc_cod ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.detalle_factura_compra_defc_cod_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          postgres    false    5    306            -           1259    24587    detalle_facturas    TABLE     �   CREATE TABLE public.detalle_facturas (
    det_cod integer NOT NULL,
    det_fact_cod integer,
    det_art_cod integer,
    det_cantidad numeric,
    det_precio_uni numeric
);
 $   DROP TABLE public.detalle_facturas;
       public         heap    postgres    false    5            /           1259    24616    detalle_facturas_det_cod_seq    SEQUENCE     �   ALTER TABLE public.detalle_facturas ALTER COLUMN det_cod ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.detalle_facturas_det_cod_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          postgres    false    301    5            �            1259    16547    detalle_fcobro    TABLE     �   CREATE TABLE public.detalle_fcobro (
    form_cod character varying NOT NULL,
    ven_cod integer NOT NULL,
    monto_fcobro integer NOT NULL
);
 "   DROP TABLE public.detalle_fcobro;
       public         heap    postgres    false    5            ;           1259    32889    detalle_orden_prod    TABLE     �   CREATE TABLE public.detalle_orden_prod (
    deor_id integer NOT NULL,
    deor_art_id integer,
    deor_orpr_id integer,
    deor_cantidad integer
);
 &   DROP TABLE public.detalle_orden_prod;
       public         heap    postgres    false    5            �            1259    16552    detalle_pedcompra    TABLE     %  CREATE TABLE public.detalle_pedcompra (
    ped_cod integer NOT NULL,
    dep_cod integer NOT NULL,
    art_cod integer NOT NULL,
    ped_cant integer NOT NULL,
    ped_precio integer NOT NULL,
    ped_precio_presup integer DEFAULT 0,
    ped_mapr_id integer,
    deped_id integer NOT NULL
);
 %   DROP TABLE public.detalle_pedcompra;
       public         heap    postgres    false    5            C           1259    49393    detalle_pedcompra_deped_id_seq    SEQUENCE     �   CREATE SEQUENCE public.detalle_pedcompra_deped_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 5   DROP SEQUENCE public.detalle_pedcompra_deped_id_seq;
       public          postgres    false    242    5            �           0    0    detalle_pedcompra_deped_id_seq    SEQUENCE OWNED BY     a   ALTER SEQUENCE public.detalle_pedcompra_deped_id_seq OWNED BY public.detalle_pedcompra.deped_id;
          public          postgres    false    323            �            1259    16555    detalle_pedventa    TABLE     �   CREATE TABLE public.detalle_pedventa (
    ped_cod integer NOT NULL,
    dep_cod integer NOT NULL,
    art_cod integer NOT NULL,
    ped_cant integer NOT NULL,
    ped_precio integer NOT NULL,
    depedven_id integer NOT NULL
);
 $   DROP TABLE public.detalle_pedventa;
       public         heap    postgres    false    5            D           1259    49401     detalle_pedventa_depedven_id_seq    SEQUENCE     �   CREATE SEQUENCE public.detalle_pedventa_depedven_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 7   DROP SEQUENCE public.detalle_pedventa_depedven_id_seq;
       public          postgres    false    243    5            �           0    0     detalle_pedventa_depedven_id_seq    SEQUENCE OWNED BY     e   ALTER SEQUENCE public.detalle_pedventa_depedven_id_seq OWNED BY public.detalle_pedventa.depedven_id;
          public          postgres    false    324            6           1259    24671    detalle_presupuesto    TABLE     �   CREATE TABLE public.detalle_presupuesto (
    depr_cod integer NOT NULL,
    depr_pres_cod integer,
    depr_art_cod integer,
    depr_precio integer DEFAULT 0,
    depr_cant integer DEFAULT 0
);
 '   DROP TABLE public.detalle_presupuesto;
       public         heap    postgres    false    5            >           1259    32926    detalle_produccion    TABLE     �   CREATE TABLE public.detalle_produccion (
    depro_id integer NOT NULL,
    depro_art_id integer,
    depro_cantidad integer,
    depro_prod_id integer
);
 &   DROP TABLE public.detalle_produccion;
       public         heap    postgres    false    5            �            1259    16558    detalle_tarjeta    TABLE     4  CREATE TABLE public.detalle_tarjeta (
    tar_cod integer NOT NULL,
    form_cod character varying NOT NULL,
    ven_cod integer NOT NULL,
    tipo_tarjeta character varying NOT NULL,
    monto_tarjeta integer NOT NULL,
    nro_tarjeta character varying NOT NULL,
    nro_cupon character varying NOT NULL
);
 #   DROP TABLE public.detalle_tarjeta;
       public         heap    postgres    false    5            �            1259    16563    detalle_ventas    TABLE       CREATE TABLE public.detalle_ventas (
    ven_cod integer NOT NULL,
    dep_cod integer NOT NULL,
    art_cod integer NOT NULL,
    ven_cant integer NOT NULL,
    ven_precio integer NOT NULL,
    exenta integer NOT NULL,
    iva_5 integer NOT NULL,
    iva_10 integer NOT NULL
);
 "   DROP TABLE public.detalle_ventas;
       public         heap    postgres    false    5            �            1259    16566    empleado    TABLE       CREATE TABLE public.empleado (
    emp_cod integer NOT NULL,
    car_cod integer NOT NULL,
    emp_nombre character varying(100),
    emp_apellido character varying(100),
    emp_direcc character varying(100) NOT NULL,
    emp_tel character varying(20) NOT NULL,
    cedula integer
);
    DROP TABLE public.empleado;
       public         heap    postgres    false    5            �            1259    16569    empresa_seq    SEQUENCE     t   CREATE SEQUENCE public.empresa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.empresa_seq;
       public          postgres    false    5            �            1259    16570    empresa    TABLE     !  CREATE TABLE public.empresa (
    codigo integer DEFAULT nextval('public.empresa_seq'::regclass) NOT NULL,
    nombre character varying(50) NOT NULL,
    direccion character varying(50) NOT NULL,
    ruc integer NOT NULL,
    telefono integer NOT NULL,
    id_sucursal integer NOT NULL
);
    DROP TABLE public.empresa;
       public         heap    postgres    false    247    5            �            1259    16574    estado_civil_seq    SEQUENCE     y   CREATE SEQUENCE public.estado_civil_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.estado_civil_seq;
       public          postgres    false    5            <           1259    32904    etapas_produccion    TABLE     p   CREATE TABLE public.etapas_produccion (
    etpr_id integer NOT NULL,
    etpr_descripcion character varying
);
 %   DROP TABLE public.etapas_produccion;
       public         heap    postgres    false    5            ,           1259    24576    facturas    TABLE     �   CREATE TABLE public.facturas (
    fact_cod integer NOT NULL,
    fact_nro character varying,
    fact_fecha_emision date,
    fact_clie_cod integer,
    fact_cliente_ruc "char",
    fact_timb_cod integer
);
    DROP TABLE public.facturas;
       public         heap    postgres    false    5            0           1259    24617    facturas_compras    TABLE     �   CREATE TABLE public.facturas_compras (
    faco_cod integer NOT NULL,
    faco_monto numeric,
    faco_nro_factura character varying,
    faco_fecha date,
    faco_prv_cod integer,
    faco_timbrado character varying
);
 $   DROP TABLE public.facturas_compras;
       public         heap    postgres    false    5            1           1259    24630    facturas_compras_faco_cod_seq    SEQUENCE     �   ALTER TABLE public.facturas_compras ALTER COLUMN faco_cod ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.facturas_compras_faco_cod_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          postgres    false    5    304            .           1259    24606    facturas_fact_cod_seq    SEQUENCE     �   ALTER TABLE public.facturas ALTER COLUMN fact_cod ADD GENERATED BY DEFAULT AS IDENTITY (
    SEQUENCE NAME public.facturas_fact_cod_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1
);
            public          postgres    false    5    300            �            1259    16580    forma_cobros    TABLE     z   CREATE TABLE public.forma_cobros (
    form_cod character varying NOT NULL,
    form_descri character varying NOT NULL
);
     DROP TABLE public.forma_cobros;
       public         heap    postgres    false    5            �            1259    16585    forma_pago_seq    SEQUENCE     w   CREATE SEQUENCE public.forma_pago_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 %   DROP SEQUENCE public.forma_pago_seq;
       public          postgres    false    5            �            1259    16586 
   forma_pago    TABLE     �   CREATE TABLE public.forma_pago (
    id_forma_pago integer DEFAULT nextval('public.forma_pago_seq'::regclass) NOT NULL,
    descripcion character varying(45) NOT NULL
);
    DROP TABLE public.forma_pago;
       public         heap    postgres    false    251    5            �            1259    16590    grupos    TABLE     l   CREATE TABLE public.grupos (
    gru_cod integer NOT NULL,
    gru_nombre character varying(40) NOT NULL
);
    DROP TABLE public.grupos;
       public         heap    postgres    false    5            �            1259    16593 	   hijos_seq    SEQUENCE     r   CREATE SEQUENCE public.hijos_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
     DROP SEQUENCE public.hijos_seq;
       public          postgres    false    5            �            1259    16599    horarios_seq    SEQUENCE     u   CREATE SEQUENCE public.horarios_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.horarios_seq;
       public          postgres    false    5                        1259    16606    horas_extras_seq    SEQUENCE     y   CREATE SEQUENCE public.horas_extras_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.horas_extras_seq;
       public          postgres    false    5                       1259    16611    ingreso_seq    SEQUENCE     t   CREATE SEQUENCE public.ingreso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.ingreso_seq;
       public          postgres    false    5                       1259    16617    ips_seq    SEQUENCE     p   CREATE SEQUENCE public.ips_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
    DROP SEQUENCE public.ips_seq;
       public          postgres    false    5                       1259    16624    legajos_seq    SEQUENCE     t   CREATE SEQUENCE public.legajos_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.legajos_seq;
       public          postgres    false    5            @           1259    32965    libro_compras    TABLE     s   CREATE TABLE public.libro_compras (
    lico_id integer NOT NULL,
    lico_comp_id integer,
    lico_fecha date
);
 !   DROP TABLE public.libro_compras;
       public         heap    postgres    false    5            ?           1259    32948    libro_ventas    TABLE     r   CREATE TABLE public.libro_ventas (
    live_id integer NOT NULL,
    live_vent_id integer,
    live_fecha date
);
     DROP TABLE public.libro_ventas;
       public         heap    postgres    false    5                       1259    16630    liquidaciones_seq    SEQUENCE     z   CREATE SEQUENCE public.liquidaciones_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.liquidaciones_seq;
       public          postgres    false    5                       1259    16636    marca    TABLE     b   CREATE TABLE public.marca (
    mar_cod integer NOT NULL,
    mar_descri character varying(60)
);
    DROP TABLE public.marca;
       public         heap    postgres    false    5                       1259    16639    marcacion_seq    SEQUENCE     v   CREATE SEQUENCE public.marcacion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.marcacion_seq;
       public          postgres    false    5                       1259    16644    material_primario    TABLE     �   CREATE TABLE public.material_primario (
    mapr_id integer NOT NULL,
    mapr_descripcion character varying NOT NULL,
    mapr_proveedor integer,
    mapr_unidad_medida character varying NOT NULL,
    mapr_precio integer,
    mapr_fecha date
);
 %   DROP TABLE public.material_primario;
       public         heap    postgres    false    5                       1259    16649    material_primario_mapr_id_seq    SEQUENCE     �   CREATE SEQUENCE public.material_primario_mapr_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 4   DROP SEQUENCE public.material_primario_mapr_id_seq;
       public          postgres    false    263    5            �           0    0    material_primario_mapr_id_seq    SEQUENCE OWNED BY     _   ALTER SEQUENCE public.material_primario_mapr_id_seq OWNED BY public.material_primario.mapr_id;
          public          postgres    false    264            	           1259    16650    mermas    TABLE     �   CREATE TABLE public.mermas (
    merm_id integer NOT NULL,
    merm_observacion character varying NOT NULL,
    merm_prod_id integer,
    merm_fecha date
);
    DROP TABLE public.mermas;
       public         heap    postgres    false    5            
           1259    16655    mermas_merm_id_seq    SEQUENCE     {   CREATE SEQUENCE public.mermas_merm_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 )   DROP SEQUENCE public.mermas_merm_id_seq;
       public          postgres    false    5    265            �           0    0    mermas_merm_id_seq    SEQUENCE OWNED BY     I   ALTER SEQUENCE public.mermas_merm_id_seq OWNED BY public.mermas.merm_id;
          public          postgres    false    266                       1259    16656    modulos    TABLE     m   CREATE TABLE public.modulos (
    mod_cod integer NOT NULL,
    mod_nombre character varying(50) NOT NULL
);
    DROP TABLE public.modulos;
       public         heap    postgres    false    5                       1259    16659    nacionalidad_seq    SEQUENCE     y   CREATE SEQUENCE public.nacionalidad_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.nacionalidad_seq;
       public          postgres    false    5                       1259    16660    nacionalidad    TABLE     �   CREATE TABLE public.nacionalidad (
    id_nacionalidad integer DEFAULT nextval('public.nacionalidad_seq'::regclass) NOT NULL,
    decripcion character varying(45) NOT NULL
);
     DROP TABLE public.nacionalidad;
       public         heap    postgres    false    268    5            :           1259    32879    orden_produccion    TABLE     �   CREATE TABLE public.orden_produccion (
    orpr_id integer NOT NULL,
    orpr_fecha_pedido date,
    orpr_estado "char",
    orpr_fecha_confe date,
    orpe_fecha_control date
);
 $   DROP TABLE public.orden_produccion;
       public         heap    postgres    false    5                       1259    16664    paginas    TABLE     �   CREATE TABLE public.paginas (
    pag_cod integer NOT NULL,
    pag_direc character varying(120) NOT NULL,
    pag_nombre character varying(80) NOT NULL,
    mod_cod integer NOT NULL
);
    DROP TABLE public.paginas;
       public         heap    postgres    false    5                       1259    16667    pedido_cabcompra    TABLE     �   CREATE TABLE public.pedido_cabcompra (
    ped_cod integer NOT NULL,
    emp_cod integer NOT NULL,
    ped_fecha date NOT NULL,
    prv_cod integer NOT NULL,
    estado character varying(1) NOT NULL,
    id_sucursal integer
);
 $   DROP TABLE public.pedido_cabcompra;
       public         heap    postgres    false    5                       1259    16670    pedido_cabventa    TABLE     �   CREATE TABLE public.pedido_cabventa (
    ped_cod integer NOT NULL,
    ped_fecha date NOT NULL,
    emp_cod integer NOT NULL,
    cli_cod integer NOT NULL,
    estado character varying(1) NOT NULL,
    id_sucursal integer
);
 #   DROP TABLE public.pedido_cabventa;
       public         heap    postgres    false    5                       1259    16673    pedido_compra    TABLE     �   CREATE TABLE public.pedido_compra (
    ped_cod integer NOT NULL,
    com_cod integer NOT NULL,
    obs_pedido character varying(60)
);
 !   DROP TABLE public.pedido_compra;
       public         heap    postgres    false    5                       1259    16676    pedido_producto    TABLE     �  CREATE TABLE public.pedido_producto (
    pepr_id integer NOT NULL,
    pepr_prod_cod integer NOT NULL,
    pepr_fecha_inicio date NOT NULL,
    pepr_cantidad integer NOT NULL,
    pepr_material character varying NOT NULL,
    pepr_presupuesto character varying NOT NULL,
    pepr_corte character varying NOT NULL,
    pepr_confeccion_ensamble character varying NOT NULL,
    pepr_acabado character varying NOT NULL,
    pepr_fecha_fin date NOT NULL
);
 #   DROP TABLE public.pedido_producto;
       public         heap    postgres    false    5                       1259    16681    pedido_producto_pepr_id_seq    SEQUENCE     �   CREATE SEQUENCE public.pedido_producto_pepr_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 2   DROP SEQUENCE public.pedido_producto_pepr_id_seq;
       public          postgres    false    5    274            �           0    0    pedido_producto_pepr_id_seq    SEQUENCE OWNED BY     [   ALTER SEQUENCE public.pedido_producto_pepr_id_seq OWNED BY public.pedido_producto.pepr_id;
          public          postgres    false    275                       1259    16682    pedido_venta    TABLE     �   CREATE TABLE public.pedido_venta (
    ped_cod integer NOT NULL,
    ven_cod integer NOT NULL,
    obs_pedido character varying(60)
);
     DROP TABLE public.pedido_venta;
       public         heap    postgres    false    5            5           1259    24663    presupuestos    TABLE     �   CREATE TABLE public.presupuestos (
    pres_cod integer NOT NULL,
    pres_ped_cod integer,
    pres_fecha_creacion date,
    pres_fecha_aprobacion date,
    pres_estado character varying DEFAULT 'P'::character varying,
    pres_clie_id integer
);
     DROP TABLE public.presupuestos;
       public         heap    postgres    false    5            8           1259    32851 
   produccion    TABLE     �   CREATE TABLE public.produccion (
    prod_id integer NOT NULL,
    prod_fecha date,
    prod_lote integer,
    prod_nro integer,
    prod_orpr_id integer,
    prod_aprobado boolean,
    prod_anho character varying
);
    DROP TABLE public.produccion;
       public         heap    postgres    false    5                       1259    16688 	   proveedor    TABLE       CREATE TABLE public.proveedor (
    prv_cod integer NOT NULL,
    prv_ruc character varying(60) NOT NULL,
    prv_razonsocial character varying(120) NOT NULL,
    prv_direccion character varying(150) NOT NULL,
    prv_telefono character varying(60) NOT NULL
);
    DROP TABLE public.proveedor;
       public         heap    postgres    false    5                       1259    16691    seccion_seq    SEQUENCE     t   CREATE SEQUENCE public.seccion_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 "   DROP SEQUENCE public.seccion_seq;
       public          postgres    false    5                       1259    16692    seccion    TABLE     �   CREATE TABLE public.seccion (
    id_seccion integer DEFAULT nextval('public.seccion_seq'::regclass) NOT NULL,
    descripcion character varying(45) DEFAULT NULL::character varying,
    id_dep_secc integer
);
    DROP TABLE public.seccion;
       public         heap    postgres    false    278    5                       1259    16697    stock    TABLE     �   CREATE TABLE public.stock (
    dep_cod integer NOT NULL,
    art_cod integer NOT NULL,
    cant_minima integer NOT NULL,
    stoc_cant integer NOT NULL,
    stoc_tipo_stock "char"
);
    DROP TABLE public.stock;
       public         heap    postgres    false    5                       1259    16700    sucursal    TABLE     i   CREATE TABLE public.sucursal (
    id_sucursal integer NOT NULL,
    suc_descri character varying(60)
);
    DROP TABLE public.sucursal;
       public         heap    postgres    false    5                       1259    16703    tarjetas    TABLE     j   CREATE TABLE public.tarjetas (
    tar_cod integer NOT NULL,
    tar_descri character varying NOT NULL
);
    DROP TABLE public.tarjetas;
       public         heap    postgres    false    5                       1259    16708    timbrado    TABLE     �   CREATE TABLE public.timbrado (
    cod_timbrado integer NOT NULL,
    nro_timbrado integer,
    vencimiento date,
    tipo_timbrado character varying(40)
);
    DROP TABLE public.timbrado;
       public         heap    postgres    false    5                       1259    16711    tipo_contrato_seq    SEQUENCE     z   CREATE SEQUENCE public.tipo_contrato_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 (   DROP SEQUENCE public.tipo_contrato_seq;
       public          postgres    false    5                       1259    16717    tipo_descuento_rrhh_seq    SEQUENCE     �   CREATE SEQUENCE public.tipo_descuento_rrhh_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 .   DROP SEQUENCE public.tipo_descuento_rrhh_seq;
       public          postgres    false    5                       1259    16723    tipo_impuesto    TABLE     �   CREATE TABLE public.tipo_impuesto (
    tipo_cod integer NOT NULL,
    tipo_descri character varying(60) NOT NULL,
    tipo_porcen integer NOT NULL
);
 !   DROP TABLE public.tipo_impuesto;
       public         heap    postgres    false    5                       1259    16726    tipo_permiso_seq    SEQUENCE     y   CREATE SEQUENCE public.tipo_permiso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 '   DROP SEQUENCE public.tipo_permiso_seq;
       public          postgres    false    5                        1259    16727    tipo_permiso    TABLE     �   CREATE TABLE public.tipo_permiso (
    id_tipo_perm integer DEFAULT nextval('public.tipo_permiso_seq'::regclass) NOT NULL,
    descripcion integer NOT NULL
);
     DROP TABLE public.tipo_permiso;
       public         heap    postgres    false    287    5            !           1259    16731    usuarios    TABLE     B  CREATE TABLE public.usuarios (
    usu_cod integer NOT NULL,
    usu_nick character varying(60) NOT NULL,
    usu_clave character varying(120) NOT NULL,
    emp_cod integer NOT NULL,
    gru_cod integer NOT NULL,
    id_sucursal integer NOT NULL,
    usu_rol character varying(10),
    intentos integer,
    fecha date
);
    DROP TABLE public.usuarios;
       public         heap    postgres    false    5            "           1259    16734 
   v_articulo    VIEW     k  CREATE VIEW public.v_articulo AS
 SELECT a.art_cod,
    a.art_codbarra,
    a.mar_cod,
    b.mar_descri,
    a.art_descri,
    a.art_precioc,
    a.art_preciov,
    a.tipo_cod,
    c.tipo_descri,
    c.tipo_porcen
   FROM ((public.articulo a
     JOIN public.marca b ON ((a.mar_cod = b.mar_cod)))
     JOIN public.tipo_impuesto c ON ((a.tipo_cod = c.tipo_cod)));
    DROP VIEW public.v_articulo;
       public          postgres    false    216    261    216    216    286    216    216    216    216    261    286    286    5            #           1259    16738 	   v_compras    VIEW     �  CREATE VIEW public.v_compras AS
SELECT
    NULL::integer AS com_cod,
    NULL::integer AS emp_cod,
    NULL::text AS empleado,
    NULL::integer AS prv_cod,
    NULL::character varying(60) AS prv_ruc,
    NULL::character varying(120) AS proveedor,
    NULL::text AS com_fecha,
    NULL::character varying(10) AS tipo_compra,
    NULL::integer AS can_cuota,
    NULL::integer AS com_plazo,
    NULL::integer AS com_total,
    NULL::text AS com_estado,
    NULL::integer AS id_sucursal,
    NULL::character varying(60) AS suc_descri,
    NULL::character varying AS totalletra,
    NULL::integer AS ped_cod,
    NULL::bigint AS com_total_precio;
    DROP VIEW public.v_compras;
       public          postgres    false    5            $           1259    16743    v_detalle_compras    VIEW     ^  CREATE VIEW public.v_detalle_compras AS
 SELECT a.com_cod,
    a.dep_cod,
    b.dep_descri,
    a.art_cod,
    c.art_descri,
    c.mar_cod,
    d.mar_descri,
    c.tipo_cod,
    e.tipo_descri,
    a.com_cant,
    a.com_precio,
    (a.com_cant * a.com_precio) AS subtotal,
    a.exenta,
    a.iva_5,
    a.iva_10
   FROM ((((public.detalle_compras a
     LEFT JOIN public.deposito b ON ((a.dep_cod = b.dep_cod)))
     LEFT JOIN public.articulo c ON ((a.art_cod = c.art_cod)))
     LEFT JOIN public.marca d ON ((c.mar_cod = d.mar_cod)))
     LEFT JOIN public.tipo_impuesto e ON ((c.tipo_cod = e.tipo_cod)));
 $   DROP VIEW public.v_detalle_compras;
       public          postgres    false    286    216    216    216    216    286    261    261    238    238    238    238    238    238    238    238    234    234    5            4           1259    24654    v_detalle_pedcompra    VIEW     >  CREATE VIEW public.v_detalle_pedcompra AS
 SELECT a.ped_cod,
    a.dep_cod,
    b.dep_descri,
    a.art_cod,
    c.art_descri,
    c.mar_cod,
    d.mar_descri,
    c.tipo_cod,
    e.tipo_descri,
    a.ped_cant,
    a.ped_precio,
    a.ped_precio_presup,
    (a.ped_cant * a.ped_precio) AS subtotal
   FROM ((((public.detalle_pedcompra a
     JOIN public.deposito b ON ((a.dep_cod = b.dep_cod)))
     JOIN public.articulo c ON ((a.art_cod = c.art_cod)))
     JOIN public.marca d ON ((c.mar_cod = d.mar_cod)))
     JOIN public.tipo_impuesto e ON ((c.tipo_cod = e.tipo_cod)));
 &   DROP VIEW public.v_detalle_pedcompra;
       public          postgres    false    216    261    261    242    242    216    216    216    234    234    242    242    286    242    286    242    5            %           1259    16753    v_detalle_pedventa    VIEW     #  CREATE VIEW public.v_detalle_pedventa AS
 SELECT a.ped_cod,
    a.dep_cod,
    b.dep_descri,
    a.art_cod,
    c.art_descri,
    c.mar_cod,
    d.mar_descri,
    c.tipo_cod,
    e.tipo_descri,
    a.ped_cant,
    a.ped_precio,
    (a.ped_cant * a.ped_precio) AS subtotal
   FROM ((((public.detalle_pedventa a
     JOIN public.deposito b ON ((a.dep_cod = b.dep_cod)))
     JOIN public.articulo c ON ((a.art_cod = c.art_cod)))
     JOIN public.marca d ON ((c.mar_cod = d.mar_cod)))
     JOIN public.tipo_impuesto e ON ((c.tipo_cod = e.tipo_cod)));
 %   DROP VIEW public.v_detalle_pedventa;
       public          postgres    false    243    261    261    286    286    216    216    216    216    234    234    243    243    243    243    5            &           1259    16758    v_detalle_ventas    VIEW     H  CREATE VIEW public.v_detalle_ventas AS
 SELECT a.ven_cod,
    a.dep_cod,
    b.dep_descri,
    a.art_cod,
    c.art_descri,
    c.mar_cod,
    d.mar_descri,
    c.tipo_cod,
    e.tipo_descri,
    a.ven_cant,
    a.ven_precio,
    (a.ven_cant * a.ven_precio) AS subtotal,
    a.exenta,
    a.iva_5,
    a.iva_10
   FROM ((((public.detalle_ventas a
     JOIN public.deposito b ON ((a.dep_cod = b.dep_cod)))
     JOIN public.articulo c ON ((a.art_cod = c.art_cod)))
     JOIN public.marca d ON ((c.mar_cod = d.mar_cod)))
     JOIN public.tipo_impuesto e ON ((c.tipo_cod = e.tipo_cod)));
 #   DROP VIEW public.v_detalle_ventas;
       public          postgres    false    245    245    245    245    245    234    245    286    234    216    261    261    245    216    216    216    245    286    5            '           1259    16768    v_pedido_cabcompra    VIEW     i  CREATE VIEW public.v_pedido_cabcompra AS
 SELECT a.ped_cod,
    to_char((a.ped_fecha)::timestamp with time zone, 'dd/mm/yyyy'::text) AS ped_fecha,
    a.emp_cod,
    (((b.emp_nombre)::text || ' '::text) || (b.emp_apellido)::text) AS empleado,
    a.prv_cod,
    c.prv_ruc,
    (c.prv_razonsocial)::text AS proveedor,
        CASE a.estado
            WHEN 'P'::text THEN 'PENDIENTE'::text
            WHEN 'C'::text THEN 'CONFIRMADO'::text
            ELSE 'ANULADO'::text
        END AS estado,
    a.id_sucursal,
    d.suc_descri,
    ( SELECT sum((detalle_pedcompra.ped_cant * detalle_pedcompra.ped_precio)) AS sum
           FROM public.detalle_pedcompra
          WHERE (detalle_pedcompra.ped_cod = a.ped_cod)) AS ped_total,
    public.convertir_letra((( SELECT sum((detalle_pedcompra.ped_cant * detalle_pedcompra.ped_precio)) AS sum
           FROM public.detalle_pedcompra
          WHERE (detalle_pedcompra.ped_cod = a.ped_cod)))::numeric) AS totalletra,
    ( SELECT sum((detalle_pedcompra.ped_cant * detalle_pedcompra.ped_precio_presup)) AS sum
           FROM public.detalle_pedcompra
          WHERE (detalle_pedcompra.ped_cod = a.ped_cod)) AS ped_total_presup,
    public.convertir_letra((( SELECT sum((detalle_pedcompra.ped_cant * detalle_pedcompra.ped_precio_presup)) AS sum
           FROM public.detalle_pedcompra
          WHERE (detalle_pedcompra.ped_cod = a.ped_cod)))::numeric) AS totalletra_presup
   FROM (((public.pedido_cabcompra a
     JOIN public.empleado b ON ((a.emp_cod = b.emp_cod)))
     JOIN public.proveedor c ON ((a.prv_cod = c.prv_cod)))
     JOIN public.sucursal d ON ((a.id_sucursal = d.id_sucursal)));
 %   DROP VIEW public.v_pedido_cabcompra;
       public          postgres    false    242    281    281    277    277    271    246    277    271    271    271    246    246    271    242    242    271    242    329    5            (           1259    16773    v_pedido_cabventa    VIEW     �  CREATE VIEW public.v_pedido_cabventa AS
 SELECT a.ped_cod,
    to_char((a.ped_fecha)::timestamp with time zone, 'dd/mm/yyyy'::text) AS ped_fecha,
    a.emp_cod,
    (((b.emp_nombre)::text || ' '::text) || (b.emp_apellido)::text) AS empleado,
    a.cli_cod,
    c.cli_ci,
    (((c.cli_nombre)::text || ' '::text) || (c.cli_apellido)::text) AS clientes,
        CASE a.estado
            WHEN 'P'::text THEN 'PENDIENTE'::text
            WHEN 'C'::text THEN 'CONFIRMADO'::text
            ELSE 'ANULADO'::text
        END AS estado,
    a.id_sucursal,
    d.suc_descri,
    ( SELECT sum((detalle_pedventa.ped_cant * detalle_pedventa.ped_precio)) AS sum
           FROM public.detalle_pedventa
          WHERE (detalle_pedventa.ped_cod = a.ped_cod)) AS ped_total,
    public.convertir_letra((( SELECT sum((detalle_pedventa.ped_cant * detalle_pedventa.ped_precio)) AS sum
           FROM public.detalle_pedventa
          WHERE (detalle_pedventa.ped_cod = a.ped_cod)))::numeric) AS totalletra
   FROM (((public.pedido_cabventa a
     JOIN public.empleado b ON ((a.emp_cod = b.emp_cod)))
     JOIN public.clientes c ON ((a.cli_cod = c.cli_cod)))
     JOIN public.sucursal d ON ((a.id_sucursal = d.id_sucursal)));
 $   DROP VIEW public.v_pedido_cabventa;
       public          postgres    false    272    224    272    272    272    272    224    224    224    329    246    246    272    281    243    243    246    243    281    5            )           1259    16778    v_stock    VIEW       CREATE VIEW public.v_stock AS
 SELECT a.art_cod,
    a.art_codbarra,
    a.mar_cod,
    b.mar_descri,
    a.art_descri,
    a.art_precioc,
    a.art_preciov,
    a.tipo_cod,
    c.tipo_descri,
    c.tipo_porcen,
    st.stoc_cant,
    d.dep_descri,
    st.dep_cod
   FROM ((((public.articulo a
     JOIN public.marca b ON ((a.mar_cod = b.mar_cod)))
     JOIN public.tipo_impuesto c ON ((a.tipo_cod = c.tipo_cod)))
     JOIN public.stock st ON ((a.art_cod = st.art_cod)))
     JOIN public.deposito d ON ((d.dep_cod = st.dep_cod)));
    DROP VIEW public.v_stock;
       public          postgres    false    216    216    216    216    216    216    216    234    234    261    261    280    280    280    286    286    286    5            B           1259    33855 
   v_usuarios    VIEW     �  CREATE VIEW public.v_usuarios AS
 SELECT a.usu_cod,
    a.usu_nick,
    a.usu_clave,
    a.emp_cod,
    (((b.emp_nombre)::text || ' '::text) || (b.emp_apellido)::text) AS empleado,
    a.gru_cod,
    d.gru_nombre,
    a.id_sucursal,
    e.suc_descri,
    b.cedula,
    a.intentos,
    a.fecha
   FROM (((public.usuarios a
     JOIN public.empleado b ON ((a.emp_cod = b.emp_cod)))
     JOIN public.grupos d ON ((a.gru_cod = d.gru_cod)))
     JOIN public.sucursal e ON ((a.id_sucursal = e.id_sucursal)));
    DROP VIEW public.v_usuarios;
       public          postgres    false    289    246    246    246    246    253    253    281    281    289    289    289    289    289    289    289    5            *           1259    16788    ventas    TABLE     �  CREATE TABLE public.ventas (
    ven_cod integer NOT NULL,
    emp_cod integer NOT NULL,
    cli_cod integer NOT NULL,
    ven_fecha date NOT NULL,
    tipo_venta character varying(10) NOT NULL,
    can_cuota integer NOT NULL,
    ven_plazo integer NOT NULL,
    ven_total integer NOT NULL,
    ven_estado character varying(1) NOT NULL,
    id_sucursal integer,
    nro_aper integer,
    caj_cod integer
);
    DROP TABLE public.ventas;
       public         heap    postgres    false    5            +           1259    16791    v_ventas    VIEW     %  CREATE VIEW public.v_ventas AS
 SELECT a.ven_cod,
    a.emp_cod,
    (((b.emp_nombre)::text || ' '::text) || (b.emp_apellido)::text) AS empleado,
    a.cli_cod,
    c.cli_ci,
    (((c.cli_nombre)::text || ' '::text) || (c.cli_apellido)::text) AS clientes,
    to_char((a.ven_fecha)::timestamp with time zone, 'dd/mm/yyyy'::text) AS ven_fecha,
    a.tipo_venta,
    a.can_cuota,
    a.ven_plazo,
    a.ven_total,
        CASE a.ven_estado
            WHEN 'P'::text THEN 'PENDIENTE'::text
            WHEN 'C'::text THEN 'CONFIRMADO'::text
            WHEN 'A'::text THEN 'ANULADO'::text
            ELSE NULL::text
        END AS ven_estado,
    a.id_sucursal,
    d.suc_descri,
    public.convertir_letra((a.ven_total)::numeric) AS totalletra,
    COALESCE(e.ped_cod, 0) AS ped_cod
   FROM ((((public.ventas a
     JOIN public.empleado b ON ((a.emp_cod = b.emp_cod)))
     JOIN public.clientes c ON ((a.cli_cod = c.cli_cod)))
     JOIN public.sucursal d ON ((a.id_sucursal = d.id_sucursal)))
     LEFT JOIN public.pedido_venta e ON ((a.ven_cod = e.ven_cod)));
    DROP VIEW public.v_ventas;
       public          postgres    false    246    329    224    224    224    224    298    246    298    298    298    298    298    246    276    276    281    281    298    298    298    298    5            �           2604    16796    control_calidad coca_id    DEFAULT     �   ALTER TABLE ONLY public.control_calidad ALTER COLUMN coca_id SET DEFAULT nextval('public.control_calidad_coca_id_seq'::regclass);
 F   ALTER TABLE public.control_calidad ALTER COLUMN coca_id DROP DEFAULT;
       public          postgres    false    228    227            �           2604    49438    ctas_a_cobrar ctco_id    DEFAULT     {   ALTER TABLE ONLY public.ctas_a_cobrar ALTER COLUMN ctco_id SET DEFAULT nextval('public.ctas_a_cobrar_ctco_seq'::regclass);
 D   ALTER TABLE public.ctas_a_cobrar ALTER COLUMN ctco_id DROP DEFAULT;
       public          postgres    false    326    229            �           2604    49421    ctas_a_pagar ctpa_id    DEFAULT     |   ALTER TABLE ONLY public.ctas_a_pagar ALTER COLUMN ctpa_id SET DEFAULT nextval('public.ctas_a_pagar_ctpa_id_seq'::regclass);
 C   ALTER TABLE public.ctas_a_pagar ALTER COLUMN ctpa_id DROP DEFAULT;
       public          postgres    false    325    230            �           2604    49448    detalle_cobros deco_id    DEFAULT     �   ALTER TABLE ONLY public.detalle_cobros ALTER COLUMN deco_id SET DEFAULT nextval('public.detalle_cobros_deco_id_seq'::regclass);
 E   ALTER TABLE public.detalle_cobros ALTER COLUMN deco_id DROP DEFAULT;
       public          postgres    false    327    237            �           2604    16797    detalle_fabricacion depr_id    DEFAULT     �   ALTER TABLE ONLY public.detalle_fabricacion ALTER COLUMN depr_id SET DEFAULT nextval('public.detalle_fabricacion_defa_id_seq'::regclass);
 J   ALTER TABLE public.detalle_fabricacion ALTER COLUMN depr_id DROP DEFAULT;
       public          postgres    false    240    239            �           2604    49394    detalle_pedcompra deped_id    DEFAULT     �   ALTER TABLE ONLY public.detalle_pedcompra ALTER COLUMN deped_id SET DEFAULT nextval('public.detalle_pedcompra_deped_id_seq'::regclass);
 I   ALTER TABLE public.detalle_pedcompra ALTER COLUMN deped_id DROP DEFAULT;
       public          postgres    false    323    242            �           2604    49402    detalle_pedventa depedven_id    DEFAULT     �   ALTER TABLE ONLY public.detalle_pedventa ALTER COLUMN depedven_id SET DEFAULT nextval('public.detalle_pedventa_depedven_id_seq'::regclass);
 K   ALTER TABLE public.detalle_pedventa ALTER COLUMN depedven_id DROP DEFAULT;
       public          postgres    false    324    243            �           2604    16798    material_primario mapr_id    DEFAULT     �   ALTER TABLE ONLY public.material_primario ALTER COLUMN mapr_id SET DEFAULT nextval('public.material_primario_mapr_id_seq'::regclass);
 H   ALTER TABLE public.material_primario ALTER COLUMN mapr_id DROP DEFAULT;
       public          postgres    false    264    263            �           2604    16799    mermas merm_id    DEFAULT     p   ALTER TABLE ONLY public.mermas ALTER COLUMN merm_id SET DEFAULT nextval('public.mermas_merm_id_seq'::regclass);
 =   ALTER TABLE public.mermas ALTER COLUMN merm_id DROP DEFAULT;
       public          postgres    false    266    265            �           2604    16800    pedido_producto pepr_id    DEFAULT     �   ALTER TABLE ONLY public.pedido_producto ALTER COLUMN pepr_id SET DEFAULT nextval('public.pedido_producto_pepr_id_seq'::regclass);
 F   ALTER TABLE public.pedido_producto ALTER COLUMN pepr_id DROP DEFAULT;
       public          postgres    false    275    274            F          0    16422    apertura_cierre 
   TABLE DATA           �   COPY public.apertura_cierre (nro_aper, caj_cod, id_sucursal, aper_fecha, usu_cod, aper_cierre, monto_aper, total_efectivo, total_cheque, total_credito, estado, cod_timbrado) FROM stdin;
    public          postgres    false    215   f�      G          0    16428    articulo 
   TABLE DATA           �   COPY public.articulo (art_cod, art_codbarra, mar_cod, art_descri, art_precioc, art_preciov, tipo_cod, art_tipo_fab) FROM stdin;
    public          postgres    false    216   '�      L          0    16460    caja 
   TABLE DATA           @   COPY public.caja (caj_cod, caj_descri, id_sucursal) FROM stdin;
    public          postgres    false    221   �      O          0    16479    clientes 
   TABLE DATA           g   COPY public.clientes (cli_cod, cli_ci, cli_nombre, cli_apellido, cli_telefono, cli_direcc) FROM stdin;
    public          postgres    false    224   T�      �          0    32862    composion_articulos 
   TABLE DATA           f   COPY public.composion_articulos (coar_id, coar_mapr_id, coar_art_id, coar_cant_requerida) FROM stdin;
    public          postgres    false    313   ��      P          0    16482    compras 
   TABLE DATA           �   COPY public.compras (com_cod, emp_cod, prv_cod, com_fecha, tipo_compra, can_cuota, com_plazo, com_total, com_estado, id_sucursal, com_estado_pago) FROM stdin;
    public          postgres    false    225   �      R          0    16492    control_calidad 
   TABLE DATA           �   COPY public.control_calidad (coca_id, coca_pepr_id, coca_inspeccion_area_acabado, coca_funciones_alcance, coca_inspeccion_total, coca_calificacion, coca_prod_id) FROM stdin;
    public          postgres    false    227   D�      �          0    32911    control_produccion 
   TABLE DATA           �   COPY public.control_produccion (copr_id, copr_prod_id, copr_etpr_id, copr_fecha, copr_estado, copr_empl_id, copr_observacion, copr_canti_producida, copr_item) FROM stdin;
    public          postgres    false    317   a�      �          0    32975    costo_produccion 
   TABLE DATA              COPY public.costo_produccion (cospr_id, cospr_prod_id, cospr_monto_produccion, cospr_monto_mano_obra, cospr_fecha) FROM stdin;
    public          postgres    false    321   ��      T          0    16496    ctas_a_cobrar 
   TABLE DATA           x   COPY public.ctas_a_cobrar (nro_cuota, ven_cod, monto_cuota, saldo_cuota, fecha_venc, estado_cuota, ctco_id) FROM stdin;
    public          postgres    false    229   ֚      U          0    16499    ctas_a_pagar 
   TABLE DATA           �   COPY public.ctas_a_pagar (nro_cuota, com_cod, monto_cuota, saldo_cuota, fecha_venc, estado_cuota, ctpa_id, ctpa_forma_pago, ctpa_fecha_pago) FROM stdin;
    public          postgres    false    230   '�      Y          0    16521    deposito 
   TABLE DATA           D   COPY public.deposito (dep_cod, dep_descri, id_sucursal) FROM stdin;
    public          postgres    false    234   }�      [          0    16532    detalle_cheques 
   TABLE DATA           �   COPY public.detalle_cheques (ban_cod, form_cod, ven_cod, tipo_cheque, monto_cheque, fecha_venc, fecha_cheque, nro_cheque) FROM stdin;
    public          postgres    false    236   ��      \          0    16537    detalle_cobros 
   TABLE DATA           �   COPY public.detalle_cobros (nro_cuota, ven_cod, monto_cobrado, deco_metodo, deco_id, deco_ctco_id, deco_fecha_pago, deco_nro_tarjeta) FROM stdin;
    public          postgres    false    237   ޛ      ]          0    16540    detalle_compras 
   TABLE DATA              COPY public.detalle_compras (dep_cod, art_cod, com_cod, com_cant, com_precio, exenta, iva_5, iva_10, deco_mapr_id) FROM stdin;
    public          postgres    false    238   �      ^          0    16543    detalle_fabricacion 
   TABLE DATA           y   COPY public.detalle_fabricacion (depr_id, depr_cod_producto, depr_cantidad, depr_precio_total, depr_prod_id) FROM stdin;
    public          postgres    false    239   @�      �          0    24631    detalle_factura_compra 
   TABLE DATA           �   COPY public.detalle_factura_compra (defc_cod, defc_com_cod, defc_faco_cod, defc_art_cod, defc_cant, defc_precio_compra) FROM stdin;
    public          postgres    false    306   ]�      �          0    24587    detalle_facturas 
   TABLE DATA           l   COPY public.detalle_facturas (det_cod, det_fact_cod, det_art_cod, det_cantidad, det_precio_uni) FROM stdin;
    public          postgres    false    301   ��      `          0    16547    detalle_fcobro 
   TABLE DATA           I   COPY public.detalle_fcobro (form_cod, ven_cod, monto_fcobro) FROM stdin;
    public          postgres    false    241   �      �          0    32889    detalle_orden_prod 
   TABLE DATA           _   COPY public.detalle_orden_prod (deor_id, deor_art_id, deor_orpr_id, deor_cantidad) FROM stdin;
    public          postgres    false    315   )�      a          0    16552    detalle_pedcompra 
   TABLE DATA           �   COPY public.detalle_pedcompra (ped_cod, dep_cod, art_cod, ped_cant, ped_precio, ped_precio_presup, ped_mapr_id, deped_id) FROM stdin;
    public          postgres    false    242   N�      b          0    16555    detalle_pedventa 
   TABLE DATA           h   COPY public.detalle_pedventa (ped_cod, dep_cod, art_cod, ped_cant, ped_precio, depedven_id) FROM stdin;
    public          postgres    false    243   ?�      �          0    24671    detalle_presupuesto 
   TABLE DATA           l   COPY public.detalle_presupuesto (depr_cod, depr_pres_cod, depr_art_cod, depr_precio, depr_cant) FROM stdin;
    public          postgres    false    310   J�      �          0    32926    detalle_produccion 
   TABLE DATA           c   COPY public.detalle_produccion (depro_id, depro_art_id, depro_cantidad, depro_prod_id) FROM stdin;
    public          postgres    false    318   ��      c          0    16558    detalle_tarjeta 
   TABLE DATA           z   COPY public.detalle_tarjeta (tar_cod, form_cod, ven_cod, tipo_tarjeta, monto_tarjeta, nro_tarjeta, nro_cupon) FROM stdin;
    public          postgres    false    244   á      d          0    16563    detalle_ventas 
   TABLE DATA           p   COPY public.detalle_ventas (ven_cod, dep_cod, art_cod, ven_cant, ven_precio, exenta, iva_5, iva_10) FROM stdin;
    public          postgres    false    245   �      e          0    16566    empleado 
   TABLE DATA           k   COPY public.empleado (emp_cod, car_cod, emp_nombre, emp_apellido, emp_direcc, emp_tel, cedula) FROM stdin;
    public          postgres    false    246   8�      g          0    16570    empresa 
   TABLE DATA           X   COPY public.empresa (codigo, nombre, direccion, ruc, telefono, id_sucursal) FROM stdin;
    public          postgres    false    248   ��      �          0    32904    etapas_produccion 
   TABLE DATA           F   COPY public.etapas_produccion (etpr_id, etpr_descripcion) FROM stdin;
    public          postgres    false    316   �      �          0    24576    facturas 
   TABLE DATA           z   COPY public.facturas (fact_cod, fact_nro, fact_fecha_emision, fact_clie_cod, fact_cliente_ruc, fact_timb_cod) FROM stdin;
    public          postgres    false    300   E�      �          0    24617    facturas_compras 
   TABLE DATA           {   COPY public.facturas_compras (faco_cod, faco_monto, faco_nro_factura, faco_fecha, faco_prv_cod, faco_timbrado) FROM stdin;
    public          postgres    false    304   ��      i          0    16580    forma_cobros 
   TABLE DATA           =   COPY public.forma_cobros (form_cod, form_descri) FROM stdin;
    public          postgres    false    250   %�      k          0    16586 
   forma_pago 
   TABLE DATA           @   COPY public.forma_pago (id_forma_pago, descripcion) FROM stdin;
    public          postgres    false    252   B�      l          0    16590    grupos 
   TABLE DATA           5   COPY public.grupos (gru_cod, gru_nombre) FROM stdin;
    public          postgres    false    253   _�      �          0    32965    libro_compras 
   TABLE DATA           J   COPY public.libro_compras (lico_id, lico_comp_id, lico_fecha) FROM stdin;
    public          postgres    false    320   ��      �          0    32948    libro_ventas 
   TABLE DATA           I   COPY public.libro_ventas (live_id, live_vent_id, live_fecha) FROM stdin;
    public          postgres    false    319   ä      t          0    16636    marca 
   TABLE DATA           4   COPY public.marca (mar_cod, mar_descri) FROM stdin;
    public          postgres    false    261   �      v          0    16644    material_primario 
   TABLE DATA           �   COPY public.material_primario (mapr_id, mapr_descripcion, mapr_proveedor, mapr_unidad_medida, mapr_precio, mapr_fecha) FROM stdin;
    public          postgres    false    263   S�      x          0    16650    mermas 
   TABLE DATA           U   COPY public.mermas (merm_id, merm_observacion, merm_prod_id, merm_fecha) FROM stdin;
    public          postgres    false    265   ��      z          0    16656    modulos 
   TABLE DATA           6   COPY public.modulos (mod_cod, mod_nombre) FROM stdin;
    public          postgres    false    267   ��      |          0    16660    nacionalidad 
   TABLE DATA           C   COPY public.nacionalidad (id_nacionalidad, decripcion) FROM stdin;
    public          postgres    false    269   �      �          0    32879    orden_produccion 
   TABLE DATA           y   COPY public.orden_produccion (orpr_id, orpr_fecha_pedido, orpr_estado, orpr_fecha_confe, orpe_fecha_control) FROM stdin;
    public          postgres    false    314   6�      }          0    16664    paginas 
   TABLE DATA           J   COPY public.paginas (pag_cod, pag_direc, pag_nombre, mod_cod) FROM stdin;
    public          postgres    false    270   k�      ~          0    16667    pedido_cabcompra 
   TABLE DATA           e   COPY public.pedido_cabcompra (ped_cod, emp_cod, ped_fecha, prv_cod, estado, id_sucursal) FROM stdin;
    public          postgres    false    271   Y�                0    16670    pedido_cabventa 
   TABLE DATA           d   COPY public.pedido_cabventa (ped_cod, ped_fecha, emp_cod, cli_cod, estado, id_sucursal) FROM stdin;
    public          postgres    false    272   (�      �          0    16673    pedido_compra 
   TABLE DATA           E   COPY public.pedido_compra (ped_cod, com_cod, obs_pedido) FROM stdin;
    public          postgres    false    273   ��      �          0    16676    pedido_producto 
   TABLE DATA           �   COPY public.pedido_producto (pepr_id, pepr_prod_cod, pepr_fecha_inicio, pepr_cantidad, pepr_material, pepr_presupuesto, pepr_corte, pepr_confeccion_ensamble, pepr_acabado, pepr_fecha_fin) FROM stdin;
    public          postgres    false    274   D�      �          0    16682    pedido_venta 
   TABLE DATA           D   COPY public.pedido_venta (ped_cod, ven_cod, obs_pedido) FROM stdin;
    public          postgres    false    276   a�      �          0    24663    presupuestos 
   TABLE DATA           �   COPY public.presupuestos (pres_cod, pres_ped_cod, pres_fecha_creacion, pres_fecha_aprobacion, pres_estado, pres_clie_id) FROM stdin;
    public          postgres    false    309   @�      �          0    32851 
   produccion 
   TABLE DATA           v   COPY public.produccion (prod_id, prod_fecha, prod_lote, prod_nro, prod_orpr_id, prod_aprobado, prod_anho) FROM stdin;
    public          postgres    false    312   ��      �          0    16688 	   proveedor 
   TABLE DATA           c   COPY public.proveedor (prv_cod, prv_ruc, prv_razonsocial, prv_direccion, prv_telefono) FROM stdin;
    public          postgres    false    277   ��      �          0    16692    seccion 
   TABLE DATA           G   COPY public.seccion (id_seccion, descripcion, id_dep_secc) FROM stdin;
    public          postgres    false    279   ��      �          0    16697    stock 
   TABLE DATA           Z   COPY public.stock (dep_cod, art_cod, cant_minima, stoc_cant, stoc_tipo_stock) FROM stdin;
    public          postgres    false    280   ��      �          0    16700    sucursal 
   TABLE DATA           ;   COPY public.sucursal (id_sucursal, suc_descri) FROM stdin;
    public          postgres    false    281   �      �          0    16703    tarjetas 
   TABLE DATA           7   COPY public.tarjetas (tar_cod, tar_descri) FROM stdin;
    public          postgres    false    282   ~�      �          0    16708    timbrado 
   TABLE DATA           Z   COPY public.timbrado (cod_timbrado, nro_timbrado, vencimiento, tipo_timbrado) FROM stdin;
    public          postgres    false    283   ��      �          0    16723    tipo_impuesto 
   TABLE DATA           K   COPY public.tipo_impuesto (tipo_cod, tipo_descri, tipo_porcen) FROM stdin;
    public          postgres    false    286   ׮      �          0    16727    tipo_permiso 
   TABLE DATA           A   COPY public.tipo_permiso (id_tipo_perm, descripcion) FROM stdin;
    public          postgres    false    288   $�      �          0    16731    usuarios 
   TABLE DATA           y   COPY public.usuarios (usu_cod, usu_nick, usu_clave, emp_cod, gru_cod, id_sucursal, usu_rol, intentos, fecha) FROM stdin;
    public          postgres    false    289   A�      �          0    16788    ventas 
   TABLE DATA           �   COPY public.ventas (ven_cod, emp_cod, cli_cod, ven_fecha, tipo_venta, can_cuota, ven_plazo, ven_total, ven_estado, id_sucursal, nro_aper, caj_cod) FROM stdin;
    public          postgres    false    298   ��      �           0    0    aguinaldo_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.aguinaldo_seq', 1, false);
          public          postgres    false    214            �           0    0    asistencia_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.asistencia_seq', 1, false);
          public          postgres    false    217            �           0    0 
   barrio_seq    SEQUENCE SET     8   SELECT pg_catalog.setval('public.barrio_seq', 2, true);
          public          postgres    false    218            �           0    0    bonificacion_familiar_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.bonificacion_familiar_seq', 1, false);
          public          postgres    false    219            �           0    0    bonificaciones_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.bonificaciones_seq', 1, false);
          public          postgres    false    220            �           0    0    cargo_rrhh_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.cargo_rrhh_seq', 1, false);
          public          postgres    false    222            �           0    0 
   ciudad_seq    SEQUENCE SET     8   SELECT pg_catalog.setval('public.ciudad_seq', 1, true);
          public          postgres    false    223            �           0    0    contrato_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.contrato_seq', 1, false);
          public          postgres    false    226            �           0    0    control_calidad_coca_id_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('public.control_calidad_coca_id_seq', 1, false);
          public          postgres    false    228            �           0    0    ctas_a_cobrar_ctco_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.ctas_a_cobrar_ctco_seq', 3, true);
          public          postgres    false    326            �           0    0    ctas_a_pagar_ctpa_id_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.ctas_a_pagar_ctpa_id_seq', 4, true);
          public          postgres    false    325            �           0    0    curriculum_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.curriculum_seq', 1, true);
          public          postgres    false    231            �           0    0    departamento_seccion_seq    SEQUENCE SET     G   SELECT pg_catalog.setval('public.departamento_seccion_seq', 1, false);
          public          postgres    false    233            �           0    0    departamento_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.departamento_seq', 2, true);
          public          postgres    false    232            �           0    0    descuento_rrhh_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.descuento_rrhh_seq', 1, false);
          public          postgres    false    235            �           0    0    detalle_cobros_deco_id_seq    SEQUENCE SET     H   SELECT pg_catalog.setval('public.detalle_cobros_deco_id_seq', 4, true);
          public          postgres    false    327            �           0    0    detalle_compras_dep_cod_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('public.detalle_compras_dep_cod_seq', 12, true);
          public          postgres    false    311            �           0    0    detalle_fabricacion_defa_id_seq    SEQUENCE SET     N   SELECT pg_catalog.setval('public.detalle_fabricacion_defa_id_seq', 1, false);
          public          postgres    false    240            �           0    0 #   detalle_factura_compra_defc_cod_seq    SEQUENCE SET     R   SELECT pg_catalog.setval('public.detalle_factura_compra_defc_cod_seq', 11, true);
          public          postgres    false    307            �           0    0    detalle_facturas_det_cod_seq    SEQUENCE SET     K   SELECT pg_catalog.setval('public.detalle_facturas_det_cod_seq', 12, true);
          public          postgres    false    303            �           0    0    detalle_pedcompra_deped_id_seq    SEQUENCE SET     M   SELECT pg_catalog.setval('public.detalle_pedcompra_deped_id_seq', 28, true);
          public          postgres    false    323            �           0    0     detalle_pedventa_depedven_id_seq    SEQUENCE SET     P   SELECT pg_catalog.setval('public.detalle_pedventa_depedven_id_seq', 100, true);
          public          postgres    false    324            �           0    0    empresa_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.empresa_seq', 1, false);
          public          postgres    false    247            �           0    0    estado_civil_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.estado_civil_seq', 2, true);
          public          postgres    false    249            �           0    0    facturas_compras_faco_cod_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('public.facturas_compras_faco_cod_seq', 41, true);
          public          postgres    false    305            �           0    0    facturas_fact_cod_seq    SEQUENCE SET     D   SELECT pg_catalog.setval('public.facturas_fact_cod_seq', 21, true);
          public          postgres    false    302            �           0    0    forma_pago_seq    SEQUENCE SET     =   SELECT pg_catalog.setval('public.forma_pago_seq', 1, false);
          public          postgres    false    251            �           0    0 	   hijos_seq    SEQUENCE SET     8   SELECT pg_catalog.setval('public.hijos_seq', 1, false);
          public          postgres    false    254            �           0    0    horarios_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.horarios_seq', 1, false);
          public          postgres    false    255            �           0    0    horas_extras_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.horas_extras_seq', 1, false);
          public          postgres    false    256            �           0    0    ingreso_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.ingreso_seq', 1, false);
          public          postgres    false    257            �           0    0    ips_seq    SEQUENCE SET     6   SELECT pg_catalog.setval('public.ips_seq', 1, false);
          public          postgres    false    258            �           0    0    legajos_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.legajos_seq', 1, false);
          public          postgres    false    259            �           0    0    liquidaciones_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.liquidaciones_seq', 1, false);
          public          postgres    false    260            �           0    0    marcacion_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.marcacion_seq', 1, false);
          public          postgres    false    262            �           0    0    material_primario_mapr_id_seq    SEQUENCE SET     L   SELECT pg_catalog.setval('public.material_primario_mapr_id_seq', 1, false);
          public          postgres    false    264            �           0    0    mermas_merm_id_seq    SEQUENCE SET     A   SELECT pg_catalog.setval('public.mermas_merm_id_seq', 1, false);
          public          postgres    false    266            �           0    0    nacionalidad_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.nacionalidad_seq', 1, false);
          public          postgres    false    268            �           0    0    pedido_producto_pepr_id_seq    SEQUENCE SET     J   SELECT pg_catalog.setval('public.pedido_producto_pepr_id_seq', 1, false);
          public          postgres    false    275            �           0    0    seccion_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.seccion_seq', 1, false);
          public          postgres    false    278            �           0    0    tipo_contrato_seq    SEQUENCE SET     @   SELECT pg_catalog.setval('public.tipo_contrato_seq', 1, false);
          public          postgres    false    284            �           0    0    tipo_descuento_rrhh_seq    SEQUENCE SET     F   SELECT pg_catalog.setval('public.tipo_descuento_rrhh_seq', 1, false);
          public          postgres    false    285            �           0    0    tipo_permiso_seq    SEQUENCE SET     ?   SELECT pg_catalog.setval('public.tipo_permiso_seq', 1, false);
          public          postgres    false    287            �           2606    16804 "   apertura_cierre apertura_cierre_pk 
   CONSTRAINT     |   ALTER TABLE ONLY public.apertura_cierre
    ADD CONSTRAINT apertura_cierre_pk PRIMARY KEY (nro_aper, caj_cod, id_sucursal);
 L   ALTER TABLE ONLY public.apertura_cierre DROP CONSTRAINT apertura_cierre_pk;
       public            postgres    false    215    215    215            �           2606    16806    articulo articulo_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.articulo
    ADD CONSTRAINT articulo_pk PRIMARY KEY (art_cod);
 >   ALTER TABLE ONLY public.articulo DROP CONSTRAINT articulo_pk;
       public            postgres    false    216            �           2606    16818    caja caja_pk 
   CONSTRAINT     O   ALTER TABLE ONLY public.caja
    ADD CONSTRAINT caja_pk PRIMARY KEY (caj_cod);
 6   ALTER TABLE ONLY public.caja DROP CONSTRAINT caja_pk;
       public            postgres    false    221            �           2606    16826    clientes clientes_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.clientes
    ADD CONSTRAINT clientes_pk PRIMARY KEY (cli_cod);
 >   ALTER TABLE ONLY public.clientes DROP CONSTRAINT clientes_pk;
       public            postgres    false    224            J           2606    32868    composion_articulos coar_pk 
   CONSTRAINT     ^   ALTER TABLE ONLY public.composion_articulos
    ADD CONSTRAINT coar_pk PRIMARY KEY (coar_id);
 E   ALTER TABLE ONLY public.composion_articulos DROP CONSTRAINT coar_pk;
       public            postgres    false    313            �           2606    16828    control_calidad coca_id 
   CONSTRAINT     Z   ALTER TABLE ONLY public.control_calidad
    ADD CONSTRAINT coca_id PRIMARY KEY (coca_id);
 A   ALTER TABLE ONLY public.control_calidad DROP CONSTRAINT coca_id;
       public            postgres    false    227            '           2606    16830    timbrado cod_timbrado 
   CONSTRAINT     ]   ALTER TABLE ONLY public.timbrado
    ADD CONSTRAINT cod_timbrado PRIMARY KEY (cod_timbrado);
 ?   ALTER TABLE ONLY public.timbrado DROP CONSTRAINT cod_timbrado;
       public            postgres    false    283            �           2606    16832    compras compras_pk 
   CONSTRAINT     U   ALTER TABLE ONLY public.compras
    ADD CONSTRAINT compras_pk PRIMARY KEY (com_cod);
 <   ALTER TABLE ONLY public.compras DROP CONSTRAINT compras_pk;
       public            postgres    false    225            R           2606    32915    control_produccion copr_pk 
   CONSTRAINT     ]   ALTER TABLE ONLY public.control_produccion
    ADD CONSTRAINT copr_pk PRIMARY KEY (copr_id);
 D   ALTER TABLE ONLY public.control_produccion DROP CONSTRAINT copr_pk;
       public            postgres    false    317            Z           2606    32981 &   costo_produccion costo_produccion_pkey 
   CONSTRAINT     j   ALTER TABLE ONLY public.costo_produccion
    ADD CONSTRAINT costo_produccion_pkey PRIMARY KEY (cospr_id);
 P   ALTER TABLE ONLY public.costo_produccion DROP CONSTRAINT costo_produccion_pkey;
       public            postgres    false    321            �           2606    49429    ctas_a_pagar cta_unique 
   CONSTRAINT     i   ALTER TABLE ONLY public.ctas_a_pagar
    ADD CONSTRAINT cta_unique UNIQUE (nro_cuota) INCLUDE (com_cod);
 A   ALTER TABLE ONLY public.ctas_a_pagar DROP CONSTRAINT cta_unique;
       public            postgres    false    230    230            �           2606    49440     ctas_a_cobrar ctas_a_cobrar_pkey 
   CONSTRAINT     c   ALTER TABLE ONLY public.ctas_a_cobrar
    ADD CONSTRAINT ctas_a_cobrar_pkey PRIMARY KEY (ctco_id);
 J   ALTER TABLE ONLY public.ctas_a_cobrar DROP CONSTRAINT ctas_a_cobrar_pkey;
       public            postgres    false    229            �           2606    49423    ctas_a_pagar ctas_a_pagar_pkey 
   CONSTRAINT     a   ALTER TABLE ONLY public.ctas_a_pagar
    ADD CONSTRAINT ctas_a_pagar_pkey PRIMARY KEY (ctpa_id);
 H   ALTER TABLE ONLY public.ctas_a_pagar DROP CONSTRAINT ctas_a_pagar_pkey;
       public            postgres    false    230            �           2606    16842    detalle_fabricacion defa_id 
   CONSTRAINT     ^   ALTER TABLE ONLY public.detalle_fabricacion
    ADD CONSTRAINT defa_id PRIMARY KEY (depr_id);
 E   ALTER TABLE ONLY public.detalle_fabricacion DROP CONSTRAINT defa_id;
       public            postgres    false    239            <           2606    24635 "   detalle_factura_compra defc_cod_pk 
   CONSTRAINT     f   ALTER TABLE ONLY public.detalle_factura_compra
    ADD CONSTRAINT defc_cod_pk PRIMARY KEY (defc_cod);
 L   ALTER TABLE ONLY public.detalle_factura_compra DROP CONSTRAINT defc_cod_pk;
       public            postgres    false    306            N           2606    32893    detalle_orden_prod deor_pk 
   CONSTRAINT     ]   ALTER TABLE ONLY public.detalle_orden_prod
    ADD CONSTRAINT deor_pk PRIMARY KEY (deor_id);
 D   ALTER TABLE ONLY public.detalle_orden_prod DROP CONSTRAINT deor_pk;
       public            postgres    false    315            �           2606    16848    deposito deposito_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.deposito
    ADD CONSTRAINT deposito_pk PRIMARY KEY (dep_cod);
 >   ALTER TABLE ONLY public.deposito DROP CONSTRAINT deposito_pk;
       public            postgres    false    234            T           2606    32930    detalle_produccion depro_pk 
   CONSTRAINT     _   ALTER TABLE ONLY public.detalle_produccion
    ADD CONSTRAINT depro_pk PRIMARY KEY (depro_id);
 E   ALTER TABLE ONLY public.detalle_produccion DROP CONSTRAINT depro_pk;
       public            postgres    false    318            5           2606    24593    detalle_facturas det_cod_pk 
   CONSTRAINT     ^   ALTER TABLE ONLY public.detalle_facturas
    ADD CONSTRAINT det_cod_pk PRIMARY KEY (det_cod);
 E   ALTER TABLE ONLY public.detalle_facturas DROP CONSTRAINT det_cod_pk;
       public            postgres    false    301            �           2606    16854 "   detalle_cheques detalle_cheques_pk 
   CONSTRAINT     x   ALTER TABLE ONLY public.detalle_cheques
    ADD CONSTRAINT detalle_cheques_pk PRIMARY KEY (ban_cod, form_cod, ven_cod);
 L   ALTER TABLE ONLY public.detalle_cheques DROP CONSTRAINT detalle_cheques_pk;
       public            postgres    false    236    236    236            �           2606    49450 "   detalle_cobros detalle_cobros_pkey 
   CONSTRAINT     e   ALTER TABLE ONLY public.detalle_cobros
    ADD CONSTRAINT detalle_cobros_pkey PRIMARY KEY (deco_id);
 L   ALTER TABLE ONLY public.detalle_cobros DROP CONSTRAINT detalle_cobros_pkey;
       public            postgres    false    237            �           2606    16858 !   detalle_compras detalle_compra_pk 
   CONSTRAINT     v   ALTER TABLE ONLY public.detalle_compras
    ADD CONSTRAINT detalle_compra_pk PRIMARY KEY (dep_cod, art_cod, com_cod);
 K   ALTER TABLE ONLY public.detalle_compras DROP CONSTRAINT detalle_compra_pk;
       public            postgres    false    238    238    238            �           2606    16860     detalle_fcobro detalle_fcobro_pk 
   CONSTRAINT     m   ALTER TABLE ONLY public.detalle_fcobro
    ADD CONSTRAINT detalle_fcobro_pk PRIMARY KEY (form_cod, ven_cod);
 J   ALTER TABLE ONLY public.detalle_fcobro DROP CONSTRAINT detalle_fcobro_pk;
       public            postgres    false    241    241            �           2606    49400 &   detalle_pedcompra detalle_pedcompra_pk 
   CONSTRAINT     j   ALTER TABLE ONLY public.detalle_pedcompra
    ADD CONSTRAINT detalle_pedcompra_pk PRIMARY KEY (deped_id);
 P   ALTER TABLE ONLY public.detalle_pedcompra DROP CONSTRAINT detalle_pedcompra_pk;
       public            postgres    false    242            �           2606    49408 $   detalle_pedventa detalle_pedventa_pk 
   CONSTRAINT     k   ALTER TABLE ONLY public.detalle_pedventa
    ADD CONSTRAINT detalle_pedventa_pk PRIMARY KEY (depedven_id);
 N   ALTER TABLE ONLY public.detalle_pedventa DROP CONSTRAINT detalle_pedventa_pk;
       public            postgres    false    243            D           2606    24676 +   detalle_presupuesto detalle_prespuesto_pkey 
   CONSTRAINT     o   ALTER TABLE ONLY public.detalle_presupuesto
    ADD CONSTRAINT detalle_prespuesto_pkey PRIMARY KEY (depr_cod);
 U   ALTER TABLE ONLY public.detalle_presupuesto DROP CONSTRAINT detalle_prespuesto_pkey;
       public            postgres    false    310            �           2606    16866 "   detalle_tarjeta detalle_tarjeta_pk 
   CONSTRAINT     x   ALTER TABLE ONLY public.detalle_tarjeta
    ADD CONSTRAINT detalle_tarjeta_pk PRIMARY KEY (tar_cod, form_cod, ven_cod);
 L   ALTER TABLE ONLY public.detalle_tarjeta DROP CONSTRAINT detalle_tarjeta_pk;
       public            postgres    false    244    244    244            �           2606    16868     detalle_ventas detalle_ventas_pk 
   CONSTRAINT     u   ALTER TABLE ONLY public.detalle_ventas
    ADD CONSTRAINT detalle_ventas_pk PRIMARY KEY (ven_cod, dep_cod, art_cod);
 J   ALTER TABLE ONLY public.detalle_ventas DROP CONSTRAINT detalle_ventas_pk;
       public            postgres    false    245    245    245            �           2606    16870    empleado empleado_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.empleado
    ADD CONSTRAINT empleado_pk PRIMARY KEY (emp_cod);
 >   ALTER TABLE ONLY public.empleado DROP CONSTRAINT empleado_pk;
       public            postgres    false    246            �           2606    16872    empresa empresa_pkey 
   CONSTRAINT     V   ALTER TABLE ONLY public.empresa
    ADD CONSTRAINT empresa_pkey PRIMARY KEY (codigo);
 >   ALTER TABLE ONLY public.empresa DROP CONSTRAINT empresa_pkey;
       public            postgres    false    248            P           2606    32910 (   etapas_produccion etapas_produccion_pkey 
   CONSTRAINT     k   ALTER TABLE ONLY public.etapas_produccion
    ADD CONSTRAINT etapas_produccion_pkey PRIMARY KEY (etpr_id);
 R   ALTER TABLE ONLY public.etapas_produccion DROP CONSTRAINT etapas_produccion_pkey;
       public            postgres    false    316            9           2606    24623    facturas_compras faco_cod_pk 
   CONSTRAINT     `   ALTER TABLE ONLY public.facturas_compras
    ADD CONSTRAINT faco_cod_pk PRIMARY KEY (faco_cod);
 F   ALTER TABLE ONLY public.facturas_compras DROP CONSTRAINT faco_cod_pk;
       public            postgres    false    304            1           2606    24580    facturas fact_cod_pk 
   CONSTRAINT     X   ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT fact_cod_pk PRIMARY KEY (fact_cod);
 >   ALTER TABLE ONLY public.facturas DROP CONSTRAINT fact_cod_pk;
       public            postgres    false    300                        2606    16876    forma_cobros forma_cobros_pk 
   CONSTRAINT     `   ALTER TABLE ONLY public.forma_cobros
    ADD CONSTRAINT forma_cobros_pk PRIMARY KEY (form_cod);
 F   ALTER TABLE ONLY public.forma_cobros DROP CONSTRAINT forma_cobros_pk;
       public            postgres    false    250                       2606    16878    forma_pago forma_pago_pkey 
   CONSTRAINT     c   ALTER TABLE ONLY public.forma_pago
    ADD CONSTRAINT forma_pago_pkey PRIMARY KEY (id_forma_pago);
 D   ALTER TABLE ONLY public.forma_pago DROP CONSTRAINT forma_pago_pkey;
       public            postgres    false    252                       2606    16880    grupos grupos_pk 
   CONSTRAINT     S   ALTER TABLE ONLY public.grupos
    ADD CONSTRAINT grupos_pk PRIMARY KEY (gru_cod);
 :   ALTER TABLE ONLY public.grupos DROP CONSTRAINT grupos_pk;
       public            postgres    false    253            X           2606    32969    libro_compras lico_pk 
   CONSTRAINT     X   ALTER TABLE ONLY public.libro_compras
    ADD CONSTRAINT lico_pk PRIMARY KEY (lico_id);
 ?   ALTER TABLE ONLY public.libro_compras DROP CONSTRAINT lico_pk;
       public            postgres    false    320            V           2606    32952    libro_ventas live_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.libro_ventas
    ADD CONSTRAINT live_pk PRIMARY KEY (live_id);
 >   ALTER TABLE ONLY public.libro_ventas DROP CONSTRAINT live_pk;
       public            postgres    false    319                       2606    16896    material_primario mapr_id 
   CONSTRAINT     \   ALTER TABLE ONLY public.material_primario
    ADD CONSTRAINT mapr_id PRIMARY KEY (mapr_id);
 C   ALTER TABLE ONLY public.material_primario DROP CONSTRAINT mapr_id;
       public            postgres    false    263                       2606    16898    marca marca_pk 
   CONSTRAINT     Q   ALTER TABLE ONLY public.marca
    ADD CONSTRAINT marca_pk PRIMARY KEY (mar_cod);
 8   ALTER TABLE ONLY public.marca DROP CONSTRAINT marca_pk;
       public            postgres    false    261                       2606    16902    mermas merm_id 
   CONSTRAINT     Q   ALTER TABLE ONLY public.mermas
    ADD CONSTRAINT merm_id PRIMARY KEY (merm_id);
 8   ALTER TABLE ONLY public.mermas DROP CONSTRAINT merm_id;
       public            postgres    false    265                       2606    16904    modulos modulos_pk 
   CONSTRAINT     U   ALTER TABLE ONLY public.modulos
    ADD CONSTRAINT modulos_pk PRIMARY KEY (mod_cod);
 <   ALTER TABLE ONLY public.modulos DROP CONSTRAINT modulos_pk;
       public            postgres    false    267                       2606    16906    nacionalidad nacionalidad_pkey 
   CONSTRAINT     i   ALTER TABLE ONLY public.nacionalidad
    ADD CONSTRAINT nacionalidad_pkey PRIMARY KEY (id_nacionalidad);
 H   ALTER TABLE ONLY public.nacionalidad DROP CONSTRAINT nacionalidad_pkey;
       public            postgres    false    269            L           2606    32883    orden_produccion orpr_pk 
   CONSTRAINT     [   ALTER TABLE ONLY public.orden_produccion
    ADD CONSTRAINT orpr_pk PRIMARY KEY (orpr_id);
 B   ALTER TABLE ONLY public.orden_produccion DROP CONSTRAINT orpr_pk;
       public            postgres    false    314                       2606    16908    paginas paginas_pk 
   CONSTRAINT     U   ALTER TABLE ONLY public.paginas
    ADD CONSTRAINT paginas_pk PRIMARY KEY (pag_cod);
 <   ALTER TABLE ONLY public.paginas DROP CONSTRAINT paginas_pk;
       public            postgres    false    270                       2606    16910    pedido_compra ped_compra_pk 
   CONSTRAINT     g   ALTER TABLE ONLY public.pedido_compra
    ADD CONSTRAINT ped_compra_pk PRIMARY KEY (ped_cod, com_cod);
 E   ALTER TABLE ONLY public.pedido_compra DROP CONSTRAINT ped_compra_pk;
       public            postgres    false    273    273                       2606    16912 $   pedido_cabcompra pedido_cabcompra_pk 
   CONSTRAINT     g   ALTER TABLE ONLY public.pedido_cabcompra
    ADD CONSTRAINT pedido_cabcompra_pk PRIMARY KEY (ped_cod);
 N   ALTER TABLE ONLY public.pedido_cabcompra DROP CONSTRAINT pedido_cabcompra_pk;
       public            postgres    false    271                       2606    16914 "   pedido_cabventa pedido_cabventa_pk 
   CONSTRAINT     e   ALTER TABLE ONLY public.pedido_cabventa
    ADD CONSTRAINT pedido_cabventa_pk PRIMARY KEY (ped_cod);
 L   ALTER TABLE ONLY public.pedido_cabventa DROP CONSTRAINT pedido_cabventa_pk;
       public            postgres    false    272                       2606    16916    pedido_venta pedido_venta_pk 
   CONSTRAINT     h   ALTER TABLE ONLY public.pedido_venta
    ADD CONSTRAINT pedido_venta_pk PRIMARY KEY (ped_cod, ven_cod);
 F   ALTER TABLE ONLY public.pedido_venta DROP CONSTRAINT pedido_venta_pk;
       public            postgres    false    276    276                       2606    16918    pedido_producto pepr_id 
   CONSTRAINT     Z   ALTER TABLE ONLY public.pedido_producto
    ADD CONSTRAINT pepr_id PRIMARY KEY (pepr_id);
 A   ALTER TABLE ONLY public.pedido_producto DROP CONSTRAINT pepr_id;
       public            postgres    false    274            B           2606    24670    presupuestos presupuestos_pkey 
   CONSTRAINT     b   ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT presupuestos_pkey PRIMARY KEY (pres_cod);
 H   ALTER TABLE ONLY public.presupuestos DROP CONSTRAINT presupuestos_pkey;
       public            postgres    false    309            H           2606    32855    produccion prod_pk 
   CONSTRAINT     U   ALTER TABLE ONLY public.produccion
    ADD CONSTRAINT prod_pk PRIMARY KEY (prod_id);
 <   ALTER TABLE ONLY public.produccion DROP CONSTRAINT prod_pk;
       public            postgres    false    312                       2606    16922    proveedor proveedor_pk 
   CONSTRAINT     Y   ALTER TABLE ONLY public.proveedor
    ADD CONSTRAINT proveedor_pk PRIMARY KEY (prv_cod);
 @   ALTER TABLE ONLY public.proveedor DROP CONSTRAINT proveedor_pk;
       public            postgres    false    277                       2606    16924    seccion seccion_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.seccion
    ADD CONSTRAINT seccion_pkey PRIMARY KEY (id_seccion);
 >   ALTER TABLE ONLY public.seccion DROP CONSTRAINT seccion_pkey;
       public            postgres    false    279            !           2606    16926    stock stock_pk 
   CONSTRAINT     Z   ALTER TABLE ONLY public.stock
    ADD CONSTRAINT stock_pk PRIMARY KEY (dep_cod, art_cod);
 8   ALTER TABLE ONLY public.stock DROP CONSTRAINT stock_pk;
       public            postgres    false    280    280            #           2606    16928    sucursal sucursal_pkey 
   CONSTRAINT     ]   ALTER TABLE ONLY public.sucursal
    ADD CONSTRAINT sucursal_pkey PRIMARY KEY (id_sucursal);
 @   ALTER TABLE ONLY public.sucursal DROP CONSTRAINT sucursal_pkey;
       public            postgres    false    281            %           2606    16930    tarjetas tarjetas_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.tarjetas
    ADD CONSTRAINT tarjetas_pk PRIMARY KEY (tar_cod);
 >   ALTER TABLE ONLY public.tarjetas DROP CONSTRAINT tarjetas_pk;
       public            postgres    false    282            )           2606    16936    tipo_impuesto tipo_impuesto_pk 
   CONSTRAINT     b   ALTER TABLE ONLY public.tipo_impuesto
    ADD CONSTRAINT tipo_impuesto_pk PRIMARY KEY (tipo_cod);
 H   ALTER TABLE ONLY public.tipo_impuesto DROP CONSTRAINT tipo_impuesto_pk;
       public            postgres    false    286            +           2606    16938    tipo_permiso tipo_permiso_pkey 
   CONSTRAINT     f   ALTER TABLE ONLY public.tipo_permiso
    ADD CONSTRAINT tipo_permiso_pkey PRIMARY KEY (id_tipo_perm);
 H   ALTER TABLE ONLY public.tipo_permiso DROP CONSTRAINT tipo_permiso_pkey;
       public            postgres    false    288            -           2606    16940    usuarios usuarios_pk 
   CONSTRAINT     W   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_pk PRIMARY KEY (usu_cod);
 >   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_pk;
       public            postgres    false    289            /           2606    16942    ventas ventas_pk 
   CONSTRAINT     S   ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT ventas_pk PRIMARY KEY (ven_cod);
 :   ALTER TABLE ONLY public.ventas DROP CONSTRAINT ventas_pk;
       public            postgres    false    298            6           1259    24599    fki_a    INDEX     J   CREATE INDEX fki_a ON public.detalle_facturas USING btree (det_fact_cod);
    DROP INDEX public.fki_a;
       public            postgres    false    301            �           1259    16943    fki_cod_timbrado    INDEX     T   CREATE INDEX fki_cod_timbrado ON public.apertura_cierre USING btree (cod_timbrado);
 $   DROP INDEX public.fki_cod_timbrado;
       public            postgres    false    215            �           1259    32992    fki_deco_matr_fk    INDEX     T   CREATE INDEX fki_deco_matr_fk ON public.detalle_compras USING btree (deco_mapr_id);
 $   DROP INDEX public.fki_deco_matr_fk;
       public            postgres    false    238            �           1259    32861    fki_defa_fabr_fk    INDEX     X   CREATE INDEX fki_defa_fabr_fk ON public.detalle_fabricacion USING btree (depr_prod_id);
 $   DROP INDEX public.fki_defa_fabr_fk;
       public            postgres    false    239            =           1259    24647    fki_defc_com_cod    INDEX     [   CREATE INDEX fki_defc_com_cod ON public.detalle_factura_compra USING btree (defc_com_cod);
 $   DROP INDEX public.fki_defc_com_cod;
       public            postgres    false    306            >           1259    24641    fki_defc_faco_fk    INDEX     \   CREATE INDEX fki_defc_faco_fk ON public.detalle_factura_compra USING btree (defc_faco_cod);
 $   DROP INDEX public.fki_defc_faco_fk;
       public            postgres    false    306            E           1259    24688    fki_depr_art_cod_fk    INDEX     [   CREATE INDEX fki_depr_art_cod_fk ON public.detalle_presupuesto USING btree (depr_art_cod);
 '   DROP INDEX public.fki_depr_art_cod_fk;
       public            postgres    false    310            F           1259    24694    fki_depr_pres_cod    INDEX     Z   CREATE INDEX fki_depr_pres_cod ON public.detalle_presupuesto USING btree (depr_pres_cod);
 %   DROP INDEX public.fki_depr_pres_cod;
       public            postgres    false    310            7           1259    24605    fki_det_art_fk    INDEX     R   CREATE INDEX fki_det_art_fk ON public.detalle_facturas USING btree (det_art_cod);
 "   DROP INDEX public.fki_det_art_fk;
       public            postgres    false    301            :           1259    24629    fki_faco_prv_fk    INDEX     T   CREATE INDEX fki_faco_prv_fk ON public.facturas_compras USING btree (faco_prv_cod);
 #   DROP INDEX public.fki_faco_prv_fk;
       public            postgres    false    304            2           1259    24586    fki_fact_clie_fk    INDEX     N   CREATE INDEX fki_fact_clie_fk ON public.facturas USING btree (fact_clie_cod);
 $   DROP INDEX public.fki_fact_clie_fk;
       public            postgres    false    300            3           1259    32850    fki_fact_timb_fk    INDEX     N   CREATE INDEX fki_fact_timb_fk ON public.facturas USING btree (fact_timb_cod);
 $   DROP INDEX public.fki_fact_timb_fk;
       public            postgres    false    300            	           1259    32947    fki_merm_prod_id    INDEX     K   CREATE INDEX fki_merm_prod_id ON public.mermas USING btree (merm_prod_id);
 $   DROP INDEX public.fki_merm_prod_id;
       public            postgres    false    265            ?           1259    32941    fki_pres_clie_fk    INDEX     Q   CREATE INDEX fki_pres_clie_fk ON public.presupuestos USING btree (pres_clie_id);
 $   DROP INDEX public.fki_pres_clie_fk;
       public            postgres    false    309            @           1259    24682    fki_pres_ped_fk    INDEX     P   CREATE INDEX fki_pres_ped_fk ON public.presupuestos USING btree (pres_ped_cod);
 #   DROP INDEX public.fki_pres_ped_fk;
       public            postgres    false    309            ;           2618    16741    v_compras _RETURN    RULE     �  CREATE OR REPLACE VIEW public.v_compras AS
 SELECT a.com_cod,
    a.emp_cod,
    (((b.emp_nombre)::text || ' '::text) || (b.emp_apellido)::text) AS empleado,
    a.prv_cod,
    p.prv_ruc,
    p.prv_razonsocial AS proveedor,
    to_char((a.com_fecha)::timestamp with time zone, 'dd/mm/yyyy'::text) AS com_fecha,
    a.tipo_compra,
    a.can_cuota,
    a.com_plazo,
    a.com_total,
        CASE a.com_estado
            WHEN 'P'::text THEN 'PENDIENTE'::text
            WHEN 'C'::text THEN 'CONFIRMADO'::text
            WHEN 'A'::text THEN 'ANULADO'::text
            ELSE NULL::text
        END AS com_estado,
    a.id_sucursal,
    d.suc_descri,
    public.convertir_letra((a.com_total)::numeric) AS totalletra,
    COALESCE(e.ped_cod, 0) AS ped_cod,
    sum((dc.com_cant * dc.com_precio)) AS com_total_precio
   FROM (((((public.compras a
     JOIN public.empleado b ON ((a.emp_cod = b.emp_cod)))
     JOIN public.proveedor p ON ((a.prv_cod = p.prv_cod)))
     JOIN public.sucursal d ON ((a.id_sucursal = d.id_sucursal)))
     LEFT JOIN public.pedido_compra e ON ((a.com_cod = e.com_cod)))
     LEFT JOIN public.detalle_compras dc ON ((a.com_cod = dc.com_cod)))
  GROUP BY a.com_cod, a.emp_cod, (((b.emp_nombre)::text || ' '::text) || (b.emp_apellido)::text), a.prv_cod, p.prv_ruc, p.prv_razonsocial, (to_char((a.com_fecha)::timestamp with time zone, 'dd/mm/yyyy'::text)), a.tipo_compra, a.can_cuota, a.com_plazo, a.com_total, a.id_sucursal, d.suc_descri, (public.convertir_letra((a.com_total)::numeric)), e.ped_cod;
 �  CREATE OR REPLACE VIEW public.v_compras AS
SELECT
    NULL::integer AS com_cod,
    NULL::integer AS emp_cod,
    NULL::text AS empleado,
    NULL::integer AS prv_cod,
    NULL::character varying(60) AS prv_ruc,
    NULL::character varying(120) AS proveedor,
    NULL::text AS com_fecha,
    NULL::character varying(10) AS tipo_compra,
    NULL::integer AS can_cuota,
    NULL::integer AS com_plazo,
    NULL::integer AS com_total,
    NULL::text AS com_estado,
    NULL::integer AS id_sucursal,
    NULL::character varying(60) AS suc_descri,
    NULL::character varying AS totalletra,
    NULL::integer AS ped_cod,
    NULL::bigint AS com_total_precio;
       public          postgres    false    238    329    225    225    225    225    225    225    225    225    225    225    238    238    246    246    246    273    273    277    277    277    281    281    3548    291            �           2620    16944    detalle_pedventa tg_art_pedido    TRIGGER     �   CREATE TRIGGER tg_art_pedido BEFORE INSERT ON public.detalle_pedventa FOR EACH ROW EXECUTE FUNCTION public.sp_verificar_articulo();
 7   DROP TRIGGER tg_art_pedido ON public.detalle_pedventa;
       public          postgres    false    354    243            �           2606    16945     ventas apertura_cierre_ventas_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT apertura_cierre_ventas_fk FOREIGN KEY (nro_aper, caj_cod, id_sucursal) REFERENCES public.apertura_cierre(nro_aper, caj_cod, id_sucursal);
 J   ALTER TABLE ONLY public.ventas DROP CONSTRAINT apertura_cierre_ventas_fk;
       public          postgres    false    298    298    215    215    215    3539    298            o           2606    16950 3   detalle_fabricacion articulo_detalle_fabricacion_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_fabricacion
    ADD CONSTRAINT articulo_detalle_fabricacion_fk FOREIGN KEY (depr_cod_producto) REFERENCES public.articulo(art_cod);
 ]   ALTER TABLE ONLY public.detalle_fabricacion DROP CONSTRAINT articulo_detalle_fabricacion_fk;
       public          postgres    false    239    216    3542            �           2606    16955 +   pedido_producto articulo_pedido_producto_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_producto
    ADD CONSTRAINT articulo_pedido_producto_fk FOREIGN KEY (pepr_prod_cod) REFERENCES public.articulo(art_cod);
 U   ALTER TABLE ONLY public.pedido_producto DROP CONSTRAINT articulo_pedido_producto_fk;
       public          postgres    false    216    3542    274            �           2606    16960    stock articulo_stock_fk    FK CONSTRAINT     ~   ALTER TABLE ONLY public.stock
    ADD CONSTRAINT articulo_stock_fk FOREIGN KEY (art_cod) REFERENCES public.articulo(art_cod);
 A   ALTER TABLE ONLY public.stock DROP CONSTRAINT articulo_stock_fk;
       public          postgres    false    280    3542    216            [           2606    16970 '   apertura_cierre caja_apertura_cierre_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.apertura_cierre
    ADD CONSTRAINT caja_apertura_cierre_fk FOREIGN KEY (caj_cod) REFERENCES public.caja(caj_cod);
 Q   ALTER TABLE ONLY public.apertura_cierre DROP CONSTRAINT caja_apertura_cierre_fk;
       public          postgres    false    215    221    3544            �           2606    16980 &   pedido_cabventa clientes_pedido_cab_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_cabventa
    ADD CONSTRAINT clientes_pedido_cab_fk FOREIGN KEY (cli_cod) REFERENCES public.clientes(cli_cod);
 P   ALTER TABLE ONLY public.pedido_cabventa DROP CONSTRAINT clientes_pedido_cab_fk;
       public          postgres    false    224    3546    272            �           2606    16985    ventas clientes_ventas_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT clientes_ventas_fk FOREIGN KEY (cli_cod) REFERENCES public.clientes(cli_cod);
 C   ALTER TABLE ONLY public.ventas DROP CONSTRAINT clientes_ventas_fk;
       public          postgres    false    224    3546    298            �           2606    32869    composion_articulos coar_art_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.composion_articulos
    ADD CONSTRAINT coar_art_fk FOREIGN KEY (coar_art_id) REFERENCES public.articulo(art_cod);
 I   ALTER TABLE ONLY public.composion_articulos DROP CONSTRAINT coar_art_fk;
       public          postgres    false    313    216    3542            �           2606    32874     composion_articulos coar_mapr_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.composion_articulos
    ADD CONSTRAINT coar_mapr_fk FOREIGN KEY (coar_mapr_id) REFERENCES public.material_primario(mapr_id);
 J   ALTER TABLE ONLY public.composion_articulos DROP CONSTRAINT coar_mapr_fk;
       public          postgres    false    263    313    3592            \           2606    16990    apertura_cierre cod_timbrado    FK CONSTRAINT     �   ALTER TABLE ONLY public.apertura_cierre
    ADD CONSTRAINT cod_timbrado FOREIGN KEY (cod_timbrado) REFERENCES public.timbrado(cod_timbrado);
 F   ALTER TABLE ONLY public.apertura_cierre DROP CONSTRAINT cod_timbrado;
       public          postgres    false    3623    283    215            m           2606    32993 !   detalle_compras comp_dete_mapr_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_compras
    ADD CONSTRAINT comp_dete_mapr_fk FOREIGN KEY (deco_mapr_id) REFERENCES public.material_primario(mapr_id) NOT VALID;
 K   ALTER TABLE ONLY public.detalle_compras DROP CONSTRAINT comp_dete_mapr_fk;
       public          postgres    false    263    238    3592            i           2606    16995 $   ctas_a_pagar compras_ctas_a_pagar_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.ctas_a_pagar
    ADD CONSTRAINT compras_ctas_a_pagar_fk FOREIGN KEY (com_cod) REFERENCES public.compras(com_cod);
 N   ALTER TABLE ONLY public.ctas_a_pagar DROP CONSTRAINT compras_ctas_a_pagar_fk;
       public          postgres    false    225    230    3548            n           2606    17000 )   detalle_compras compras_detalle_compra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_compras
    ADD CONSTRAINT compras_detalle_compra_fk FOREIGN KEY (com_cod) REFERENCES public.compras(com_cod);
 S   ALTER TABLE ONLY public.detalle_compras DROP CONSTRAINT compras_detalle_compra_fk;
       public          postgres    false    225    238    3548            �           2606    17005 #   pedido_compra compras_ped_compra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_compra
    ADD CONSTRAINT compras_ped_compra_fk FOREIGN KEY (com_cod) REFERENCES public.compras(com_cod);
 M   ALTER TABLE ONLY public.pedido_compra DROP CONSTRAINT compras_ped_compra_fk;
       public          postgres    false    273    3548    225            �           2606    32960    control_produccion copr_empl_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.control_produccion
    ADD CONSTRAINT copr_empl_fk FOREIGN KEY (copr_empl_id) REFERENCES public.empleado(emp_cod) NOT VALID;
 I   ALTER TABLE ONLY public.control_produccion DROP CONSTRAINT copr_empl_fk;
       public          postgres    false    246    3580    317            �           2606    32921    control_produccion copr_etpr_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.control_produccion
    ADD CONSTRAINT copr_etpr_fk FOREIGN KEY (copr_etpr_id) REFERENCES public.etapas_produccion(etpr_id);
 I   ALTER TABLE ONLY public.control_produccion DROP CONSTRAINT copr_etpr_fk;
       public          postgres    false    317    3664    316            �           2606    32916    control_produccion copr_prod_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.control_produccion
    ADD CONSTRAINT copr_prod_fk FOREIGN KEY (copr_prod_id) REFERENCES public.produccion(prod_id);
 I   ALTER TABLE ONLY public.control_produccion DROP CONSTRAINT copr_prod_fk;
       public          postgres    false    3656    312    317            �           2606    32982    costo_produccion copro_prod_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.costo_produccion
    ADD CONSTRAINT copro_prod_fk FOREIGN KEY (cospr_prod_id) REFERENCES public.produccion(prod_id);
 H   ALTER TABLE ONLY public.costo_produccion DROP CONSTRAINT copro_prod_fk;
       public          postgres    false    312    321    3656            l           2606    49455 $   detalle_cobros cta_cobrar_detalle_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_cobros
    ADD CONSTRAINT cta_cobrar_detalle_fk FOREIGN KEY (deco_ctco_id) REFERENCES public.ctas_a_cobrar(ctco_id) NOT VALID;
 N   ALTER TABLE ONLY public.detalle_cobros DROP CONSTRAINT cta_cobrar_detalle_fk;
       public          postgres    false    229    237    3552            p           2606    32856     detalle_fabricacion defa_fabr_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_fabricacion
    ADD CONSTRAINT defa_fabr_fk FOREIGN KEY (depr_prod_id) REFERENCES public.produccion(prod_id);
 J   ALTER TABLE ONLY public.detalle_fabricacion DROP CONSTRAINT defa_fabr_fk;
       public          postgres    false    3656    239    312            �           2606    24642 #   detalle_factura_compra defc_com_cod    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_factura_compra
    ADD CONSTRAINT defc_com_cod FOREIGN KEY (defc_com_cod) REFERENCES public.compras(com_cod);
 M   ALTER TABLE ONLY public.detalle_factura_compra DROP CONSTRAINT defc_com_cod;
       public          postgres    false    306    3548    225            �           2606    24636 #   detalle_factura_compra defc_faco_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_factura_compra
    ADD CONSTRAINT defc_faco_fk FOREIGN KEY (defc_faco_cod) REFERENCES public.facturas_compras(faco_cod);
 M   ALTER TABLE ONLY public.detalle_factura_compra DROP CONSTRAINT defc_faco_fk;
       public          postgres    false    3641    306    304            �           2606    32894    detalle_orden_prod deor_arti_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_orden_prod
    ADD CONSTRAINT deor_arti_fk FOREIGN KEY (deor_art_id) REFERENCES public.articulo(art_cod);
 I   ALTER TABLE ONLY public.detalle_orden_prod DROP CONSTRAINT deor_arti_fk;
       public          postgres    false    216    315    3542            �           2606    32899    detalle_orden_prod deor_orpr_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_orden_prod
    ADD CONSTRAINT deor_orpr_fk FOREIGN KEY (deor_orpr_id) REFERENCES public.orden_produccion(orpr_id);
 I   ALTER TABLE ONLY public.detalle_orden_prod DROP CONSTRAINT deor_orpr_fk;
       public          postgres    false    315    3660    314            �           2606    17015    stock deposito_stock_fk    FK CONSTRAINT     ~   ALTER TABLE ONLY public.stock
    ADD CONSTRAINT deposito_stock_fk FOREIGN KEY (dep_cod) REFERENCES public.deposito(dep_cod);
 A   ALTER TABLE ONLY public.stock DROP CONSTRAINT deposito_stock_fk;
       public          postgres    false    280    3558    234            �           2606    24683 #   detalle_presupuesto depr_art_cod_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_presupuesto
    ADD CONSTRAINT depr_art_cod_fk FOREIGN KEY (depr_art_cod) REFERENCES public.articulo(art_cod);
 M   ALTER TABLE ONLY public.detalle_presupuesto DROP CONSTRAINT depr_art_cod_fk;
       public          postgres    false    216    310    3542            �           2606    24689 !   detalle_presupuesto depr_pres_cod    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_presupuesto
    ADD CONSTRAINT depr_pres_cod FOREIGN KEY (depr_pres_cod) REFERENCES public.presupuestos(pres_cod);
 K   ALTER TABLE ONLY public.detalle_presupuesto DROP CONSTRAINT depr_pres_cod;
       public          postgres    false    309    3650    310            �           2606    32931    detalle_produccion depro_art_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_produccion
    ADD CONSTRAINT depro_art_fk FOREIGN KEY (depro_art_id) REFERENCES public.articulo(art_cod) NOT VALID;
 I   ALTER TABLE ONLY public.detalle_produccion DROP CONSTRAINT depro_art_fk;
       public          postgres    false    216    3542    318            �           2606    49411     detalle_produccion depro_prod_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_produccion
    ADD CONSTRAINT depro_prod_fk FOREIGN KEY (depro_prod_id) REFERENCES public.produccion(prod_id) NOT VALID;
 J   ALTER TABLE ONLY public.detalle_produccion DROP CONSTRAINT depro_prod_fk;
       public          postgres    false    312    318    3656            �           2606    24600    detalle_facturas det_art_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_facturas
    ADD CONSTRAINT det_art_fk FOREIGN KEY (det_art_cod) REFERENCES public.articulo(art_cod);
 E   ALTER TABLE ONLY public.detalle_facturas DROP CONSTRAINT det_art_fk;
       public          postgres    false    3542    216    301            �           2606    24594    detalle_facturas det_fact_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_facturas
    ADD CONSTRAINT det_fact_fk FOREIGN KEY (det_fact_cod) REFERENCES public.facturas(fact_cod);
 F   ALTER TABLE ONLY public.detalle_facturas DROP CONSTRAINT det_fact_fk;
       public          postgres    false    3633    300    301            k           2606    17020 1   detalle_cheques detalle_fcobro_detalle_cheques_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_cheques
    ADD CONSTRAINT detalle_fcobro_detalle_cheques_fk FOREIGN KEY (form_cod, ven_cod) REFERENCES public.detalle_fcobro(form_cod, ven_cod);
 [   ALTER TABLE ONLY public.detalle_cheques DROP CONSTRAINT detalle_fcobro_detalle_cheques_fk;
       public          postgres    false    241    3570    236    236    241            x           2606    17025 1   detalle_tarjeta detalle_fcobro_detalle_tarjeta_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_tarjeta
    ADD CONSTRAINT detalle_fcobro_detalle_tarjeta_fk FOREIGN KEY (form_cod, ven_cod) REFERENCES public.detalle_fcobro(form_cod, ven_cod);
 [   ALTER TABLE ONLY public.detalle_tarjeta DROP CONSTRAINT detalle_fcobro_detalle_tarjeta_fk;
       public          postgres    false    244    244    241    241    3570            b           2606    17030    compras empleado_compras_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.compras
    ADD CONSTRAINT empleado_compras_fk FOREIGN KEY (emp_cod) REFERENCES public.empleado(emp_cod);
 E   ALTER TABLE ONLY public.compras DROP CONSTRAINT empleado_compras_fk;
       public          postgres    false    3580    225    246            �           2606    17035 &   pedido_cabventa empleado_pedido_cab_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_cabventa
    ADD CONSTRAINT empleado_pedido_cab_fk FOREIGN KEY (emp_cod) REFERENCES public.empleado(emp_cod);
 P   ALTER TABLE ONLY public.pedido_cabventa DROP CONSTRAINT empleado_pedido_cab_fk;
       public          postgres    false    3580    272    246                       2606    17040 *   pedido_cabcompra empleado_pedido_compra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_cabcompra
    ADD CONSTRAINT empleado_pedido_compra_fk FOREIGN KEY (emp_cod) REFERENCES public.empleado(emp_cod);
 T   ALTER TABLE ONLY public.pedido_cabcompra DROP CONSTRAINT empleado_pedido_compra_fk;
       public          postgres    false    246    3580    271            �           2606    17045    usuarios empleado_usuarios_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT empleado_usuarios_fk FOREIGN KEY (emp_cod) REFERENCES public.empleado(emp_cod);
 G   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT empleado_usuarios_fk;
       public          postgres    false    246    3580    289            �           2606    17050    ventas empleado_ventas_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT empleado_ventas_fk FOREIGN KEY (emp_cod) REFERENCES public.empleado(emp_cod);
 C   ALTER TABLE ONLY public.ventas DROP CONSTRAINT empleado_ventas_fk;
       public          postgres    false    246    3580    298            �           2606    24624    facturas_compras faco_prv_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.facturas_compras
    ADD CONSTRAINT faco_prv_fk FOREIGN KEY (faco_prv_cod) REFERENCES public.proveedor(prv_cod);
 F   ALTER TABLE ONLY public.facturas_compras DROP CONSTRAINT faco_prv_fk;
       public          postgres    false    3613    277    304            �           2606    24581    facturas fact_clie_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT fact_clie_fk FOREIGN KEY (fact_clie_cod) REFERENCES public.clientes(cli_cod);
 ?   ALTER TABLE ONLY public.facturas DROP CONSTRAINT fact_clie_fk;
       public          postgres    false    3546    224    300            �           2606    32845    facturas fact_timb_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.facturas
    ADD CONSTRAINT fact_timb_fk FOREIGN KEY (fact_timb_cod) REFERENCES public.timbrado(cod_timbrado);
 ?   ALTER TABLE ONLY public.facturas DROP CONSTRAINT fact_timb_fk;
       public          postgres    false    3623    283    300            a           2606    17080    caja fk_caja_surcusal    FK CONSTRAINT     �   ALTER TABLE ONLY public.caja
    ADD CONSTRAINT fk_caja_surcusal FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 ?   ALTER TABLE ONLY public.caja DROP CONSTRAINT fk_caja_surcusal;
       public          postgres    false    281    3619    221            q           2606    17205 -   detalle_fcobro forma_cobros_detalle_fcobro_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_fcobro
    ADD CONSTRAINT forma_cobros_detalle_fcobro_fk FOREIGN KEY (form_cod) REFERENCES public.forma_cobros(form_cod);
 W   ALTER TABLE ONLY public.detalle_fcobro DROP CONSTRAINT forma_cobros_detalle_fcobro_fk;
       public          postgres    false    241    250    3584            �           2606    17215    usuarios grupos_usuarios_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT grupos_usuarios_fk FOREIGN KEY (gru_cod) REFERENCES public.grupos(gru_cod);
 E   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT grupos_usuarios_fk;
       public          postgres    false    289    253    3588            �           2606    32970    libro_compras lico_comp_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.libro_compras
    ADD CONSTRAINT lico_comp_fk FOREIGN KEY (lico_comp_id) REFERENCES public.compras(com_cod);
 D   ALTER TABLE ONLY public.libro_compras DROP CONSTRAINT lico_comp_fk;
       public          postgres    false    225    3548    320            �           2606    32953    libro_ventas live_vent_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.libro_ventas
    ADD CONSTRAINT live_vent_fk FOREIGN KEY (live_vent_id) REFERENCES public.ventas(ven_cod);
 C   ALTER TABLE ONLY public.libro_ventas DROP CONSTRAINT live_vent_fk;
       public          postgres    false    3631    319    298            _           2606    17270    articulo marca_articulo_fk    FK CONSTRAINT     ~   ALTER TABLE ONLY public.articulo
    ADD CONSTRAINT marca_articulo_fk FOREIGN KEY (mar_cod) REFERENCES public.marca(mar_cod);
 D   ALTER TABLE ONLY public.articulo DROP CONSTRAINT marca_articulo_fk;
       public          postgres    false    261    3590    216            }           2606    32942    mermas merm_prod_id    FK CONSTRAINT     �   ALTER TABLE ONLY public.mermas
    ADD CONSTRAINT merm_prod_id FOREIGN KEY (merm_prod_id) REFERENCES public.produccion(prod_id);
 =   ALTER TABLE ONLY public.mermas DROP CONSTRAINT merm_prod_id;
       public          postgres    false    3656    312    265            ~           2606    17275    paginas modulos_interfaces_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.paginas
    ADD CONSTRAINT modulos_interfaces_fk FOREIGN KEY (mod_cod) REFERENCES public.modulos(mod_cod);
 G   ALTER TABLE ONLY public.paginas DROP CONSTRAINT modulos_interfaces_fk;
       public          postgres    false    3597    270    267            s           2606    17280 7   detalle_pedcompra pedido_cabcompra_detalle_pedcompra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_pedcompra
    ADD CONSTRAINT pedido_cabcompra_detalle_pedcompra_fk FOREIGN KEY (ped_cod) REFERENCES public.pedido_cabcompra(ped_cod);
 a   ALTER TABLE ONLY public.detalle_pedcompra DROP CONSTRAINT pedido_cabcompra_detalle_pedcompra_fk;
       public          postgres    false    271    242    3603            �           2606    17285 ,   pedido_compra pedido_cabcompra_ped_compra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_compra
    ADD CONSTRAINT pedido_cabcompra_ped_compra_fk FOREIGN KEY (ped_cod) REFERENCES public.pedido_cabcompra(ped_cod);
 V   ALTER TABLE ONLY public.pedido_compra DROP CONSTRAINT pedido_cabcompra_ped_compra_fk;
       public          postgres    false    3603    271    273            v           2606    17290 2   detalle_pedventa pedido_cabecera_pedido_detalle_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_pedventa
    ADD CONSTRAINT pedido_cabecera_pedido_detalle_fk FOREIGN KEY (ped_cod) REFERENCES public.pedido_cabventa(ped_cod);
 \   ALTER TABLE ONLY public.detalle_pedventa DROP CONSTRAINT pedido_cabecera_pedido_detalle_fk;
       public          postgres    false    243    272    3605            �           2606    17295 ,   pedido_venta pedido_cabecera_pedido_venta_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_venta
    ADD CONSTRAINT pedido_cabecera_pedido_venta_fk FOREIGN KEY (ped_cod) REFERENCES public.pedido_cabventa(ped_cod);
 V   ALTER TABLE ONLY public.pedido_venta DROP CONSTRAINT pedido_cabecera_pedido_venta_fk;
       public          postgres    false    276    3605    272            t           2606    32998 '   detalle_pedcompra pedido_compra_mapr_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_pedcompra
    ADD CONSTRAINT pedido_compra_mapr_fk FOREIGN KEY (ped_mapr_id) REFERENCES public.material_primario(mapr_id) NOT VALID;
 Q   ALTER TABLE ONLY public.detalle_pedcompra DROP CONSTRAINT pedido_compra_mapr_fk;
       public          postgres    false    263    3592    242            e           2606    17300 2   control_calidad pedido_producto_control_calidad_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.control_calidad
    ADD CONSTRAINT pedido_producto_control_calidad_fk FOREIGN KEY (coca_pepr_id) REFERENCES public.pedido_producto(pepr_id);
 \   ALTER TABLE ONLY public.control_calidad DROP CONSTRAINT pedido_producto_control_calidad_fk;
       public          postgres    false    227    274    3609            �           2606    32936    presupuestos pres_clie_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT pres_clie_fk FOREIGN KEY (pres_clie_id) REFERENCES public.clientes(cli_cod);
 C   ALTER TABLE ONLY public.presupuestos DROP CONSTRAINT pres_clie_fk;
       public          postgres    false    224    309    3546            �           2606    24677    presupuestos pres_ped_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.presupuestos
    ADD CONSTRAINT pres_ped_fk FOREIGN KEY (pres_ped_cod) REFERENCES public.pedido_cabcompra(ped_cod);
 B   ALTER TABLE ONLY public.presupuestos DROP CONSTRAINT pres_ped_fk;
       public          postgres    false    3603    271    309            f           2606    49431    control_calidad prod_control_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.control_calidad
    ADD CONSTRAINT prod_control_fk FOREIGN KEY (coca_prod_id) REFERENCES public.produccion(prod_id) NOT VALID;
 I   ALTER TABLE ONLY public.control_calidad DROP CONSTRAINT prod_control_fk;
       public          postgres    false    312    227    3656            �           2606    32884    produccion prod_orpr_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.produccion
    ADD CONSTRAINT prod_orpr_fk FOREIGN KEY (prod_orpr_id) REFERENCES public.orden_produccion(orpr_id);
 A   ALTER TABLE ONLY public.produccion DROP CONSTRAINT prod_orpr_fk;
       public          postgres    false    312    3660    314            c           2606    17310    compras proveedor_compras_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.compras
    ADD CONSTRAINT proveedor_compras_fk FOREIGN KEY (prv_cod) REFERENCES public.proveedor(prv_cod);
 F   ALTER TABLE ONLY public.compras DROP CONSTRAINT proveedor_compras_fk;
       public          postgres    false    225    277    3613            �           2606    17315 +   pedido_cabcompra proveedor_pedido_compra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_cabcompra
    ADD CONSTRAINT proveedor_pedido_compra_fk FOREIGN KEY (prv_cod) REFERENCES public.proveedor(prv_cod);
 U   ALTER TABLE ONLY public.pedido_cabcompra DROP CONSTRAINT proveedor_pedido_compra_fk;
       public          postgres    false    277    3613    271            u           2606    17325 ,   detalle_pedcompra stock_detalle_pedcompra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_pedcompra
    ADD CONSTRAINT stock_detalle_pedcompra_fk FOREIGN KEY (dep_cod, art_cod) REFERENCES public.stock(dep_cod, art_cod);
 V   ALTER TABLE ONLY public.detalle_pedcompra DROP CONSTRAINT stock_detalle_pedcompra_fk;
       public          postgres    false    3617    280    242    242    280            z           2606    17330 &   detalle_ventas stock_detalle_ventas_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_ventas
    ADD CONSTRAINT stock_detalle_ventas_fk FOREIGN KEY (dep_cod, art_cod) REFERENCES public.stock(dep_cod, art_cod);
 P   ALTER TABLE ONLY public.detalle_ventas DROP CONSTRAINT stock_detalle_ventas_fk;
       public          postgres    false    280    245    245    3617    280            w           2606    17335 (   detalle_pedventa stock_pedido_detalle_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_pedventa
    ADD CONSTRAINT stock_pedido_detalle_fk FOREIGN KEY (dep_cod, art_cod) REFERENCES public.stock(dep_cod, art_cod);
 R   ALTER TABLE ONLY public.detalle_pedventa DROP CONSTRAINT stock_pedido_detalle_fk;
       public          postgres    false    280    280    243    243    3617            ]           2606    17340 +   apertura_cierre sucursal_apertura_cierre_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.apertura_cierre
    ADD CONSTRAINT sucursal_apertura_cierre_fk FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 U   ALTER TABLE ONLY public.apertura_cierre DROP CONSTRAINT sucursal_apertura_cierre_fk;
       public          postgres    false    281    215    3619            d           2606    17345    compras sucursal_compras_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.compras
    ADD CONSTRAINT sucursal_compras_fk FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 E   ALTER TABLE ONLY public.compras DROP CONSTRAINT sucursal_compras_fk;
       public          postgres    false    3619    225    281            j           2606    17350    deposito sucursal_deposito_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.deposito
    ADD CONSTRAINT sucursal_deposito_fk FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 G   ALTER TABLE ONLY public.deposito DROP CONSTRAINT sucursal_deposito_fk;
       public          postgres    false    234    281    3619            |           2606    17355    empresa sucursal_empresa_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.empresa
    ADD CONSTRAINT sucursal_empresa_fk FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 E   ALTER TABLE ONLY public.empresa DROP CONSTRAINT sucursal_empresa_fk;
       public          postgres    false    3619    248    281            �           2606    17360 -   pedido_cabcompra sucursal_pedido_cabcompra_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_cabcompra
    ADD CONSTRAINT sucursal_pedido_cabcompra_fk FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 W   ALTER TABLE ONLY public.pedido_cabcompra DROP CONSTRAINT sucursal_pedido_cabcompra_fk;
       public          postgres    false    3619    271    281            �           2606    17365 +   pedido_cabventa sucursal_pedido_cabventa_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_cabventa
    ADD CONSTRAINT sucursal_pedido_cabventa_fk FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 U   ALTER TABLE ONLY public.pedido_cabventa DROP CONSTRAINT sucursal_pedido_cabventa_fk;
       public          postgres    false    281    3619    272            �           2606    17370    ventas sucursal_ventas_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.ventas
    ADD CONSTRAINT sucursal_ventas_fk FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 C   ALTER TABLE ONLY public.ventas DROP CONSTRAINT sucursal_ventas_fk;
       public          postgres    false    298    3619    281            y           2606    17375 +   detalle_tarjeta tarjetas_detalle_tarjeta_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_tarjeta
    ADD CONSTRAINT tarjetas_detalle_tarjeta_fk FOREIGN KEY (tar_cod) REFERENCES public.tarjetas(tar_cod);
 U   ALTER TABLE ONLY public.detalle_tarjeta DROP CONSTRAINT tarjetas_detalle_tarjeta_fk;
       public          postgres    false    282    244    3621            `           2606    17380 "   articulo tipo_impuesto_articulo_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.articulo
    ADD CONSTRAINT tipo_impuesto_articulo_fk FOREIGN KEY (tipo_cod) REFERENCES public.tipo_impuesto(tipo_cod);
 L   ALTER TABLE ONLY public.articulo DROP CONSTRAINT tipo_impuesto_articulo_fk;
       public          postgres    false    286    216    3625            ^           2606    17385 *   apertura_cierre usuario_apertura_cierre_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.apertura_cierre
    ADD CONSTRAINT usuario_apertura_cierre_fk FOREIGN KEY (usu_cod) REFERENCES public.usuarios(usu_cod);
 T   ALTER TABLE ONLY public.apertura_cierre DROP CONSTRAINT usuario_apertura_cierre_fk;
       public          postgres    false    215    289    3629            �           2606    17390 "   usuarios usuarios_id_sucursal_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.usuarios
    ADD CONSTRAINT usuarios_id_sucursal_fkey FOREIGN KEY (id_sucursal) REFERENCES public.sucursal(id_sucursal);
 L   ALTER TABLE ONLY public.usuarios DROP CONSTRAINT usuarios_id_sucursal_fkey;
       public          postgres    false    281    3619    289            g           2606    17395 %   ctas_a_cobrar ventas_ctas_a_cobrar_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.ctas_a_cobrar
    ADD CONSTRAINT ventas_ctas_a_cobrar_fk FOREIGN KEY (ven_cod) REFERENCES public.ventas(ven_cod);
 O   ALTER TABLE ONLY public.ctas_a_cobrar DROP CONSTRAINT ventas_ctas_a_cobrar_fk;
       public          postgres    false    3631    298    229            h           2606    17400 #   ctas_a_cobrar ventas_ctas_cobrar_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.ctas_a_cobrar
    ADD CONSTRAINT ventas_ctas_cobrar_fk FOREIGN KEY (ven_cod) REFERENCES public.ventas(ven_cod);
 M   ALTER TABLE ONLY public.ctas_a_cobrar DROP CONSTRAINT ventas_ctas_cobrar_fk;
       public          postgres    false    3631    298    229            r           2606    17405 '   detalle_fcobro ventas_detalle_fcobro_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_fcobro
    ADD CONSTRAINT ventas_detalle_fcobro_fk FOREIGN KEY (ven_cod) REFERENCES public.ventas(ven_cod);
 Q   ALTER TABLE ONLY public.detalle_fcobro DROP CONSTRAINT ventas_detalle_fcobro_fk;
       public          postgres    false    3631    298    241            {           2606    17410 '   detalle_ventas ventas_detalle_ventas_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.detalle_ventas
    ADD CONSTRAINT ventas_detalle_ventas_fk FOREIGN KEY (ven_cod) REFERENCES public.ventas(ven_cod);
 Q   ALTER TABLE ONLY public.detalle_ventas DROP CONSTRAINT ventas_detalle_ventas_fk;
       public          postgres    false    3631    298    245            �           2606    17415 #   pedido_venta ventas_pedido_venta_fk    FK CONSTRAINT     �   ALTER TABLE ONLY public.pedido_venta
    ADD CONSTRAINT ventas_pedido_venta_fk FOREIGN KEY (ven_cod) REFERENCES public.ventas(ven_cod);
 M   ALTER TABLE ONLY public.pedido_venta DROP CONSTRAINT ventas_pedido_venta_fk;
       public          postgres    false    3631    298    276            F   �  x���Q�� @��.�a���9Eh-Վ�1�{bp��a@X,�k��s�o�i�o��X�Ab�i)�����z�(��Aا�س�J.�\(�*>;4ˠ�����|��Îk�4E��*P��� ��+��Z`i�Y%���#�Oĭ�"xg��=��E������+Q%��h���VY��i:����)���W�UUJ&�s�*q�*�7M��ʣU��w���@�V���0N'�c�F��T�q*g�j�!�|g]���;)�4�}GǾ�C��r�*���Б��.�$��Ae����Nw`|�8>�Y/P��/�(%!l�j���zY�iƮ�͒�N}H��gc��rҍA	�"���H��"���Z"����`ϏE�%�z��ӉO7x�fy*�=l��}?:�����~~n���Oe����?Eߗ�      G   �   x�m��n� E�׏i�Ӗ�6(]�����(hѷ��$�ɽ�q�7��UV�U�@�c�1w��0 ��3�D���1*��[k�se�)��%�����x(b� FS�:j�]AԌ�7�i�S�zMq�G�!)�tG�(��D|�|�е�����%�O������
J��/M���6�(!�DR�FfE����������/+��dn��/]��4.QX      L   9   x�3�tv�rT0�4�2�0��LӐӈ�����&j�eaZ B�f@f� P��      O   M  x�=P�n�0;S_�/�,�R�㋫�*)��`5zQSw�&��C�~rZ��� �$D-�o�}�����y������Ɵ�/�׌�i�Y�D��Ev)+%�½��_��{�V-��8D�����|��|��y<��ZbE��1��p)���n:2!!�2���p�۰��ZΨ��-�+
w��%b�����Sh]aL��M10q�y���;�������q�4Dʮy�*��U��V�RK�����<{���D�8W��J�����ON�c��>�}<��q���v:� &�nk#��/�"�vI(YO��kJ��陽�`��r�l$      �   "   x�3�4A#.#�HqZp� i ���� `��      P   Q  x���Kn�0 �ur��N�,G�,�TͲ�?G�'�L�`		=�Ʊ�A���ǌ����������dە�Ѹ�Ni�D
ȯ�*j�� �����޳��'<j2�W��Rm���d��X��Z��w�A'���`�VA���u!^ճ� +R)nu��ժj��˽�.�X������>�8$��q(*rI]��3�&u�dE*峌���g��Z����~������N�@�д|N%ߐ'��*�f���=�/9�����s����R@.����b��{��~}q�]T��J�buΦJ���	u5�^���	��Y���J� 	�-{JkOaal��e��\�MM      R      x������ � �      �   1   x�3�4�4�4202�54�5��t
psr��21~@9#�=... +�      �   $   x�3�4�44 NCcid`d�kh�k`����� T	      T   A   x�3�47�47 (id`d�kh�kd��i���46K�$A����\F�`9]��1W� ���      U   F   x�3�45�4�450�42� Q1~�����@�1BB�%�!�&I��	D�!ahji ���i������ �A`      Y   4   x�3�q��4�2�tq���7�L�<#NC.CNgW�� G ;F��� >d      [      x������ � �      \   -   x�3�47�47 NWNc 4202�54�52��2B�4A������ 5�	�      ]     x�uS۵!��brDM�`����լ!q=��(A@��3X���$Mz`��xX8�P@�f2vt!��%�ēƓ�PϕT�u��k��x�s�&��vrBk&����ѭC�oU��:���N1rV�.�r
���q�YQznE��&�|�M9;j���csM�2���Ԯ��²:�s�57c�dx��7��%�r]�k���:�أn5f���3����A��u�忎;f�孋kn������ �n��VI�W�� �������J)���y      ^      x������ � �      �   +   x�34�45�4��4�4�42 .CC��1�!H��В+F��� �F�      �   d   x�M���0C��0^C[mwq�9L���^�,HpU��YW�ܹ������e���45OM�������GM��k7�NE�oN;u�;�U��r� ߡO      `      x������ � �      �      x�3�4�4�4����� ��      a   �   x�m�ۍ� D�=Ŭ��y4���븘�V�B�Θ�LPQ������ �_Qh��C�P"9�����	�b`�2�i0�����66Ү�ǠBG�?��͹Iu��c��Gw���8���V��>M�z�W�A�OW��O�C8�+O7o�N����.��u��}�|H0�g�ki>����!$q+ɕ�ZVm���3���@��}]4�-��Ny^�uy\��� ���i      b   �  x�]�[�� D�S��KD�r�?��y@g��;�H��=tui��1�:5�]t�������!����s�<�^��v)���XG0�	����Pz�:�?�2=N ��>U�a�V�H��j����@��=V=�F�{#Q$���N=�:�}��'p8ʁ��3���d�����!u�q��-P,��v����Y5���"����E$ .h�$;
���
4����r����.uY ۵���ƍ@�=
��M%P��cd�Nr���,�N�H�$�t'Yd�]�}[��ĜkA�|�T����ˬ�|�D��Nd9ʪ.u2[����`�&��^��|2XJrq�U�YfQi���=��4�egԬ�:��8%HY�HY�2�􆳇��'��	ޑ"U���3������¯)���~)�E��`���pA����ҔK0W1�&�>���V�ڲ��}���$�ڳ�ݡ�y��t�=���y��fh���:��*sV���ڊ9�Bk���?�x�      �   D   x�%���@��PL�a�%�����ND���;a*.F�d8���Q� [��$��Z76��L�3�z�M      �      x�3�4�4�4����� ��      c      x������ � �      d   H   x�3�4�4bC�Q@�e5ʙB�.�z�#�B�RS�R�!P13�vd1s�eȚ��|NKt�=... �K      e   �   x�M�A�0E��Spc[`_�њ�!]7հD	�N� .�ɬ��?Á��b�վ@ȕ?`����J�
e�BBLI(L��S{0�� !�U�[� ~�
J�u�r�E�M�f�4MY|�f�׸H��du=�hi(W�3[C)9,��O�|�ڮ�y�WLG�,%�xu��a�ϗ�n;��2R6�      g      x������ � �      �   !   x�3�t��KKMN����2r�JR��b���� rsT      �   _   x�m��� ��s+�����4���	_��c�9TT3%k�c\s&�d 78J����:��}C���B��[1w�����6n0���'��      �   a   x�U���0��.E�M�م��Q%T���>Sƌ!�A�@�0H9�6��(2\�ګ�3;��	գē�x�es͙�� Z\��	���@�2<�����S      i      x������ � �      k      x������ � �      l   7   x�3�LL����,.)JL�/�2�LN�J-��2�L��-(J,�2�,K�+2b���� ��*      �      x������ � �      �      x������ � �      t   c   x����@��_� a��'���fy� $���8z����Bs〞��c��,�mͅ�E%�"-���*�3:�*V��Л���]vΌ��6�{ ��      v   /   x�3�I�IT(�,�W0�����44  ��I�*g
������ �uJ      x      x������ � �      z   Z   x��K
�0E�q�b�NC����� ��u�م���e�O��-�1R���V%�ͯ�3e9����[#c��N-��n�XCH��: ?.q�      |      x������ � �      �   %   x�3�4202�5��5��t�pu�9c��b���� hP�      }   �  x�mS�n�0</�B_�F��G#I� -j4mO����J"A�A��]J��F|��p3d	-�G����'_�JU�!�� o&��4�gI^�33K�I����1��X�z���1���zg����}�š�0�V�F��O��i��{��R�W��]P6�5 P��OF�և3���5��	�UYK���ٚ�Sah�r�e�N=/(��,\��!4r��	��*אb�`����e��o���m�{���_/����&v�;6Ń�=�kUn!PN�K�R�������X��0بr��#����J� =�><jK!�Q���LD�V�T�8�lv��{�=���;�@1���@g�|���)T�1�o�P'#��N$ B}�5��Z��%x"g���P�0��U��GV�v �?�)r��m�?ڀs1Ƴ������0��~�(����^����O�F�{
����z���Y��Y�;�?d�gy\(��A�y�      ~   �   x�m�K� е�c~aYu_� ��9�!�Ē'c�!����H?:��lC�<1�	�-B1>Ӯ��I�#je���9���H�:J����>��1
�{ۥ�j�}�R�v��\��ʻ��Z�֩ը,��Oվ���a��r+ˡ+o5n5پQ��e]���Ũ��ը��C���ލ�U������ba]         �  x�m�Kn�0б��W���V�@�����qL��::�ņG+��\�`�.p�c;	&��h�9�§���L�ғ��UM���
�R��u̳�GǾ��X'B�:Lq+�]  ��RT4eW�lsWqUW�����c�{MFT��=�9]���怘f)UN�e�Z�}Ĳ�Y��E�
Vavѣ���<��i�y�W�g���A>��OV����(�:M��#��u���m�M�u�����شg�z,lK��2�fx�)F]=��0Ŭ��-��iV���l¬�͸gui���[�#[�V���.���n!3���Eu����+c;,m�|9�5��i�R�XBL��`�KϏN]��GV��?(���O�Y�j?��+�v�r�uܜ�o���u��:NS��}��� �      �   v   x�5��� ߢ�l� M\鿎8rxy�a%�����l}s�s�{���i�B`p�F��A�eDY�����1�2�)=�)=�)=�)=�IA��TN�@��T�/���ʹ��&c*E      �      x������ � �      �   �   x�5��q�@D�s+���$���U��}�������{�7�������b��+�ъa�b� �Sq�#�3��YJ�9[���D�[�\O�\�D�'G.���U�5*�Zr]*d�*d�
٩Bv������+#���}��}��2r���%#wˀm5`��Wؗ��Հs��yC>��|n���jSC���Zk�}GC��%�KK��9��d�[�      �   =   x�3�42�4202�50�52�2-u��89c��L8�,���9�,����:������ ��      �   !   x�3�4202�54�50�4�4�?�=... G��      �   �   x���n�0Dg�+�n+R���C4[�U\vlH��|}d��<��`����A����_�5��+d�L��m����Y�8(�q
�F��A���/���>����W<��kwP,",�1�aT�5�؇��@\O��PW���R�Ƥf�oK���S���{#�$�	~¤�y]���X�Q�!��b�y#�=V      �      x������ � �      �   [   x�M���0�3S��� ��Q'U��9�$K��GMn��;G��t�-Xe��\�̯���L4���Pt�A'�wG:g`?�y���u      �   T   x�3�,N�S�*I��%��%�\��n�A~�~.�
.�
>�
��A�\f���~
^��~\F�>����\�`!'�� O?�=... ��.      �      x������ � �      �   ,   x�3�4�4202�54�52���2�4����Db���� ���      �   =   x�3�t�p�q�4�2�trstqT0U�4�2q�@\CUNC.N�0GN#S�=... ^�       �      x������ � �      �   �   x�����@��9s�{G�4��P�qK�P `,�z���3��!�����1����idk%�JS!��z	�'`�l�3:x��㟈7�3�h
�!4�Z��<�������u��2�b{ʏ�!�%�^۱]��g��>�o�!2�a�A��T      �   ?  x���Mn�0���]� ����A���4@�e����m���*	���2�C:4?ѣ�f�No������/z~����'�X04���H@�p��1x~0ͬ|�E�m�Ph:	��NB��򭴢��_^_4�}��ud��$�8L�0)-�!2Z�T��B&K�6�GIJ���A�D���
1�Kq�{�p�m��$��	T��L��D�i�0	{��&qX��d��0)ȅ�&I�hV�ڧ�q4���n�H/]$�U����rё45_����|��wmb0�����^��J��b�^l�Q^���I�Ȟe�j]�9��<��ᆁ�}���jwN�v�7��U�d�6��KE�ʸ��i�ԙ/������MJ�њ�͞������a�ل�&���\�(��ݧ*�&D��V`Ӆ�0���~N����a�E��$���S7�Z%G(���\�:��p5�չ-�����W'#��N�m'�6O ��+�=��FH��*')�\��\)'q��h�N�h�Ш���4�T8.��� QJ����,�d�t{C)��p��c��QB���c��B+��[:�M��?��s�.��     