FROM gvenzl/oracle-free:23
ENV ORACLE_PASSWORD="oracle_password"
ENV APP_USER="app_user"
ENV APP_USER_PASSWORD="app_user_password"
ADD https://github.com/oracle-samples/db-sample-schemas.git /tmp/db-sample-schemas/
ADD oracle/sql_scripts/init_01.sql /container-entrypoint-startdb.d/init_01.sql
ADD oracle/sql_scripts/init_02.sql /container-entrypoint-startdb.d/init_02.sql
ADD oracle/sql_scripts/init_03.sql /container-entrypoint-startdb.d/init_03.sql
ADD oracle/sql_scripts/init_04.sql /container-entrypoint-startdb.d/init_04.sql

EXPOSE 1521