FROM ubuntu:22.04

ARG XAMPP_URL="http://sourceforge.net/projects/xampp/files/XAMPP%20Linux/5.6.3/xampp-linux-x64-5.6.3-0-installer.run/download"

ENV DEBIAN_FRONTEND=noninteractive \
    XAMPP_PATH=/opt/lampp \
    PATH="/opt/lampp/bin:${PATH}"

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        net-tools \
        libaio1 \
        libncurses5 \
        libx11-6 \
        libxpm4 \
        libxt6 \
        libgtk2.0-0 \
        libglu1-mesa \
        libsm6 \
    && curl -L "$XAMPP_URL" -o /tmp/xampp-installer.run \
    && chmod +x /tmp/xampp-installer.run \
    && /tmp/xampp-installer.run --mode unattended \
    && rm /tmp/xampp-installer.run \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /var/www \
    && rm -rf /opt/lampp/htdocs \
    && ln -s /var/www /opt/lampp/htdocs

WORKDIR /var/www
COPY . /var/www

RUN chown -R daemon:daemon /var/www \
    && touch /opt/lampp/logs/error_log

COPY configs/local-vhosts.conf /opt/lampp/etc/extra/local-vhosts.conf
RUN sed -i 's/\r$//' /opt/lampp/etc/extra/local-vhosts.conf \
    && grep -qF "Include etc/extra/local-vhosts.conf" /opt/lampp/etc/httpd.conf \
    || echo "Include etc/extra/local-vhosts.conf" >> /opt/lampp/etc/httpd.conf

COPY entrypoint.sh /entrypoint.sh
RUN sed -i 's/\r$//' /entrypoint.sh \
    && chmod +x /entrypoint.sh

EXPOSE 80 3306
CMD ["/entrypoint.sh"]
