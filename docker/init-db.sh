#!/bin/bash
set -e

echo ">> Importando employeesdb..."
cd /sql

sed '/set storage_engine/d' employees.sql > /tmp/employees_fixed.sql
mysql --force -u root -p"$MYSQL_ROOT_PASSWORD" < /tmp/employees_fixed.sql

echo ">> Import finalizado. Revisa arriba si hubo 'ERROR'."
