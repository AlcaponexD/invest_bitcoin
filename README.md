Projeto de carteira de bitcoin com dados alimentados pela API do mercadobitcoin.com.br

Lumen 8

#Comandos necessários


php artisan jwt:secret
#Testar a aplicação PHPUNIT
vendor\bin\phpunit

#Jobs
php artisan queue:work

Alterar no dotEnv para  QUEUE_CONNECTION=database

#Adicionar ao DotENV
API_KEY_MANDRILL="suaKEY"


#Link para documentação da api
https://documenter.getpostman.com/view/10183183/TVmMgxRs

#Considerações
O item 11) Histórico, não foi executado, visto que a api nao oferece um overview do dia todo, para eu pegar de 10 em 10 min provavelmente eu executaria um servico em background de 10 em 10 min (cron por exemplo), mas como seria algo "simples" e oque já foi executado é ate mais avançado que o tema deixei o mesmo de lado.
