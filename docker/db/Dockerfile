FROM postgres:17.2-bookworm

# Define the build argument
ARG POSTGRES_DB

RUN apt update && apt install -y ca-certificates
RUN yes | /usr/share/postgresql-common/pgdg/apt.postgresql.org.sh
RUN apt update
RUN apt -y install postgresql-17-cron

RUN echo "shared_preload_libraries = 'pg_cron'" >> /usr/share/postgresql/postgresql.conf.sample
RUN echo "cron.database_name = '${POSTGRES_DB}'" >> /usr/share/postgresql/postgresql.conf.sample
CMD ["postgres", "-c", "config_file=/usr/share/postgresql/postgresql.conf.sample"]

RUN mkdir -p /docker-entrypoint-initdb.d

COPY ./docker/db/db_init_plus_example_data.sql /docker-entrypoint-initdb.d/
COPY . /app/database