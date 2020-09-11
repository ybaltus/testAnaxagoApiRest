# Var —————————————————————————————————————————————————————————————————————————
SUDO			= sudo
ROOT_DOCUMENT	= $(shell pwd)/public
EXEC_PHP      	= php
GIT           	= git

NO_COLOR		= \x1b[0m
INFO_COLOR		= \x1b[96;01m
ERROR_COLOR		= \x1b[91;01m
SUCCEED_COLOR	= \x1b[32;01m

# Setup ———————————————————————————————————————————————————————————————————————
help: ## help : Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

# Configuration Build ————————————————————————————————————————————————————————————————
start-project: ## Create private.key public.key with passphrase
	$(SUDO) ./add-host.sh 127.0.0.1 starter.anaxago.local.com
	@echo -e "Host starter.anaxago.local.com ajouté"
	cat vhost-anaxago.conf.dist | sed -e "s+DOCUMENT_ROOT+$(ROOT_DOCUMENT)+" >> vhost-anaxago.conf
	@echo -e "Génération des dossiers de logs"
	$(SUDO) mkdir -p /var/log/apache2/anaxago-starter
	$(SUDO) mkdir -p /var/log/apache2/anaxago-starter
	$(SUDO) mkdir -p /var/log/apache2/anaxago-starter
	@echo -e "Dossiers de logs générés"
	$(SUDO) cp vhost-anaxago.conf /etc/apache2/sites-available/starter-anaxago.conf
	$(SUDO) a2ensite starter-anaxago.conf
	$(SUDO) systemctl restart apache2.service
	@echo -e "Host prêt à l'emploi"
	php bin/console doc:dat:cre
	php bin/console doc:sch:up --force
	php bin/console doc:fix:load
	@echo -e "Rendez-vous sur http://starter.anaxago.local.com "

clean-starter-project: ## Create private.key public.key with passphrase
	@echo -e "Suppression du host Anaxago Starter"
	$(SUDO) ./remove-host.sh starter.anaxago.local.com
	$(SUDO) rm -rf /etc/apache2/sites-enabled/starter-anaxago.conf
	$(SUDO) systemctl restart apache2.service
	@echo -e "Anaxago Starter n'éxiste plus à bientot !!"