FROM php:7.4-apache

COPY . /var/www/html

WORKDIR /var/www/html/src

RUN docker-php-ext-install mysqli

RUN echo $(openssl rand -hex 16) > /root/root.txt

RUN useradd -m -s /bin/bash user && \
chown -R user:user /var/www/html/src/uploads && \
chmod -R 755 /var/www/html/src/uploads

RUN echo $(openssl rand -hex 16) > /home/user/user.txt

RUN apt-get update && apt-get install -y wget && \
    wget https://packages.wazuh.com/4.x/apt/pool/main/w/wazuh-agent/wazuh-agent_4.11.0-1_amd64.deb && WAZUH_MANAGER='172.16.5.2' WAZUH_AGENT_NAME='web_forum' dpkg -i ./wazuh-agent_4.11.0-1_amd64.deb && \
    sudo systemctl daemon-reload && \
    sudo systemctl enable wazuh-agent && \
    sudo systemctl start wazuh-agent

USER user

EXPOSE 80

CMD ["php", "-S", "0.0.0.0:80"]