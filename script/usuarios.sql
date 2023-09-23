create view v_usuarios as
SELECT a.usu_cod, a.usu_nick, a.usu_clave, a.emp_cod,
b.car_cod,c.car_descri, (b.emp_nombre||' '||b.emp_apellido) as empleado,
 a.gru_cod,d.gru_nombre, a.id_sucursal,e.suc_descri
  FROM usuarios a
  join empleado b on a.emp_cod = b.emp_cod
  join cargo c on b.car_cod = c.car_cod
  join grupos d on a.gru_cod = d.gru_cod
  join sucursal e on a.id_sucursal = e.id_sucursal;
