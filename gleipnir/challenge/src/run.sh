#!/bin/bash

sed -i 's/None/All/g' /etc/apache2/apache2.conf
service mysql start 1>/dev/null && \
		mysql < /root/sqlSetup && \
        mysql -u username -ph00dwink inv3ntory < /root/database.sql && \
		service apache2 start > /dev/null 2>&1 && \
        echo "[#] Challenge can be accessed at: http://$(hostname -I)" && \
        tail -f /dev/null