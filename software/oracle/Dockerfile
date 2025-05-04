FROM gvenzl/oracle-free:23
ENV ORACLE_PASSWORD="oracle_password"
ENV APP_USER="app_user"
ENV APP_USER_PASSWORD="app_user_password"
ADD https://github.com/oracle-samples/db-sample-schemas.git /tmp/db-sample-schemas/
ADD oracle/install_hr.sh /container-entrypoint-startdb.d/00_install_hr.sh

EXPOSE 1521