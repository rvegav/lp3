create view v_articulo as
SELECT a.art_cod, a.art_codbarra, a.mar_cod,b.mar_descri, a.art_descri, a.art_precioc, a.art_preciov, 
       a.tipo_cod, c.tipo_descri, c.tipo_porcen
  FROM articulo a
  join marca b on a.mar_cod = b.mar_cod
  join tipo_impuesto c on a.tipo_cod = c.tipo_cod;
