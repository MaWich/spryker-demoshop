
FROM claranet/spryker-base:0.9.4-php71-alpine

LABEL org.label-schema.name="claranet/spryker-demoshop" \
      org.label-schema.version="2.24.1" \
      org.label-schema.description="Dockerized Spyker Demoshop" \
      org.label-schema.vendor="Claranet GmbH" \
      org.label-schema.schema-version="1.0" \
      org.label-schema.vcs-url="https://github.com/claranet/spryker-demoshop" \
      author1="Fabian Dörk <fabian.doerk@de.clara.net>" \
      author2="Tony Fahrion <tony.fahrion@de.clara.net>"

COPY ./data $WORKDIR/data
