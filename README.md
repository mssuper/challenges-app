Aplicação Challenges-app 
==================================================

* Criado um novo módulo chamado Usuário
* Criada entidade de usuário
* Implementado autenticação de usuário (com login e senha)
* Implementado um filtro de acesso para restringir o acesso a certas páginas apenas para usuários autenticados
* Implementado UI de gerenciamento de usuário
* Itens do menu principal de inicialização de maneira diferente com base no fato de o usuário atual estar conectado ou não

## Instalação

Você precisa ter o servidor Apache 2.4 HTTP, PHP v.5.6 ou posterior com extensões `gd` e` intl`, e MySQL 5.6 ou posterior ou use um webserver local
entre na pasta do aplicativo por windows cmd ou linux shell e digite:

php -S localhost:8080 -t /public 



Download o Challenges-app para alguma pasta  (pode ser seu diretório inicial ou `/var/www/html`) e execute o Composer como segue:

```
php composer.phar install
```

O comando acima irá instalar as dependências (Zend Framework e Doctrine).

Habilite o modo de desenvolvimento:

```
php composer.phar development-enable
```

Faça Login no MySQL client:

```
mysql -u root -p
```

Criar o banco de dados:

```
CREATE
USER 'challenges-app'@'localhost' IDENTIFIED WITH mysql_native_password AS '***';GRANT USAGE ON *.* TO
'challenges-app'@'localhost' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;CREATE
DATABASE IF NOT EXISTS `challenges-app`;GRANT ALL PRIVILEGES ON `challenges-app`.* TO
'challenges-app'@'localhost';GRANT ALL PRIVILEGES ON `challenges-app\_%`.* TO
'challenges-app'@'localhost';
quit
```

Rodar a migração de Banco de Dados migrations para inicializar o schema  :
```
No linux
./vendor/bin/doctrine-module migrations:migrate
```
```
No Windows
vendor\bin\doctrine-module.bat migrations:migrate
```
O Resultado será algo como:
C:\Users\Marcelo\Documents\Zend\projeto\challenges-app>vendor\bin\doctrine-module.bat migrations:migrate
Loading configuration from the integration code of your framework (setter).

                    Doctrine Database Migrations


WARNING! You are about to execute a database migration that could result in schema changes and data lost. Are you sure you wish to continue? (y/n)y
Migrating up to 20160924162137 from 0

++ migrating 20160924162137

     -> CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(128) NOT NULL, full_name VARCHAR(512) NOT NULL, password VARCHAR(256) NOT NULL, status INT NOT NULL, date_created DATETIME NOT NULL, pwd_reset_token VARCHAR(256) DEFAULT NULL, pwd_reset_token_creation_date DATETIME DEFAULT NULL, UNIQUE INDEX email_idx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB

++ migrated (1.53s)

  ------------------------

++ finished in 1.53s
++ 1 migrations executed
++ 1 sql queries

Agora você deve conseguir ver o site de Demonstração do Usuário visitando o link "http://localhost:8080".

## Licença

Este código é fornecido sob o [BSD-like license](https://en.wikipedia.org/wiki/BSD_licenses).

## Contribuição

Se você encontrou um erro ou bug, relate-o usando
A Página [Questões](https://github.com/mssuper/challenges-app/issues). Os seus comentários são extremamente apreciados.
