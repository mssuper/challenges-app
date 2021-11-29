Aplicação Challenges-app 
==================================================

* Criado um novo módulo chamado Usuário
* Criada entidade de usuário
* Implementado autenticação de usuário (com login e senha)
* Implementado um filtro de acesso para restringir o acesso a certas páginas apenas para usuários autenticados
* Implementado UI de gerenciamento de usuário
* Itens do menu principal de inicialização de maneira diferente com base no fato de o usuário atual estar conectado ou não

## Installation

Você precisa ter o servidor Apache 2.4 HTTP, PHP v.5.6 ou posterior com extensões `gd` e` intl`, e MySQL 5.6 ou posterior.

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

Run database migrations to intialize database schema:
```
./vendor/bin/doctrine-module migrations:migrate
```

Na Pasta Raiz digite php 0.0.0.0:8080 -t /public 

Agora você deve conseguir ver o site de Demonstração do Usuário visitando o link "http://localhost:8080".

## License

Este código é fornecido sob o [BSD-like license](https://en.wikipedia.org/wiki/BSD_licenses).

## Contributing

Se você encontrou um erro ou bug, relate-o usando
A Página [Questões](https://github.com/mssuper/challenges-app/issues). Os seus comentários são extremamente apreciados.
