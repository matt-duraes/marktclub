<?php

use App\Classes\AuthApp\Scope;
use App\Helpers\Painel\ConfiguracoesPadrao;

return [
    [
        'id'                 => '1',
        'uuid'               => '1e01bddf-6ba5-437c-9dba-003f31988f71',
        'id_admin_empresa'   => '1',
        'nome'               => 'Painel - Interno',
        'descricao'          => 'App para o painel',
        'imagem_app'         => null,
        'chave_privada'      => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCm5Ch8IdET+Fmn
MCp75JMRJOAgWQlpQm5jQFo+9Ko6MfVRbLUEaofcDwshmNLTgsD1XX1SU05vyRvd
otZssyPExrJlVNefiG1bErr6UJ/dk1jD/iBFjVbrtlNRIp9738DsOOJCALQ+Tph0
9dykXab5g869cwGdiwQayoNEnDfKBXvK3oo8Ojjrebf2r5/+P26ZRfTQ+QgjCgLZ
9l7To93JOgnBiiVAe5FWlvIuvyrGMVWxNJTHw9aXk0hOjsSByiGdE5kKjpHRhjzQ
f6ZFh+XS5YGLutqx5uPMaWXa7i7UMyJ4XPAqJvUeQrRUIBTeay72r9G9m2/cB8ND
/XT1c8MBAgMBAAECggEAAJaTHdpH9VVvQ6eOOmAalHyc9YEG1D1Q325TjkwyGbD0
u8GbTJ2PZsTFTeW7wxVsNp+RAJRZu75f7leQc9l4rbnJcp66Z1EozIGlhJp6SikN
DsMtC/20zpe2ooJLTfMV2zQTBRDhhPFlnptOuKQTwaZq2Qm7ZqaEQE/SJ5Cse1yc
RCWIo8rz45j6UgZuJuH0ygc0Xp7vO5hPABnCjObQVnWlz2fpkVTWL+sDueQWaAZN
Rwkmz9TxqvrBewKncLZM52lq2eh8FNnZDgSqddsAAojQiyeW8SEqMGqF88QB7LHn
Qs2AxTtEd/MCSTyRNVkoRWQOQAZmUnI5sD0uvh/tqQKBgQDruFru3H96w4RsN8Z3
GXjYxDz97nlZ2+n8f9P2MXrNx3scRFOiXyfDUkImgGUyBTCJ4IERKRAoNJlsiUuX
VcOco/d3Va/6ly7xTW26u2E1uJCikZgCJwrfkjT6DXihyrU16SZpyUEizGA3418r
ks27iTSDC3B+LGSl3xenrzsd2QKBgQC1P9+IDzBUpC9abZXPWk6G/2iPx8+Pi0Uo
1s/O5KU1f5Y6w7TWH7HTxm2hCAd1WJ2Y0UA4oYyj2+XelDUgoTcviKwedspDnoC9
oECFoaLxrb3wVDxJRH/yoDbW01mv1h3oOuhqk4HSMMUorqamKvwN7tnxQZZ8CjEc
YXdZDmSNaQKBgQCRBG81KoRnA2gxp7K6zPny6e/YDWGs7cWrKP0/Jju147aSslp9
t2rgGHhH9Y/MUTMGcA8XfprJEWseQe02YnYgpSN20EmesmoX3BnY2rS4dx7MVSQe
luRynSFogOcpKmuHijOuuzkObov3djOzu/JEIOokgOIpTahx/6ku6XhGKQKBgF/R
58PPe9aTgjFFU/juHivCZS32DWYu0542imAvgqPY0rw4cKbu1J1H/vct/ntsIM0E
evY1edu6yji0k62vbTRlBdGJLX84EYmuac372N/54Ttn3QNDAn1rc/J4g6axBNic
I/kMCIrtm53ZT2LzwJNBHdksunjTDomZgXYEZOZZAoGAMn1fWQnqAK8M9X0tHOV1
dVZ8Z7TFB54j+ok+m4jRD+PpuSLxZ5n4abWh5rbj/COrUedQaWUurnJMtH9E+cLU
kUI8Dg1HmZxhjfQPW1DGaTPDHnGLqkGKhSPTX4S442m4JjEVaQfCWAW/BJjazB1g
mS8Cd37KqsyeRA5oQmuOJtY=
-----END PRIVATE KEY-----',
        'chave_privada_fake' => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCm5Ch8IdET+Fmn
MCp75JMRJOAgWQlpQm5jQFo+9Ko6MfVRbLUEaofcDwshmNLTgsD1XX1SU05vyRvd
otZssyPExrJlVNefiG1bErr6UJ/dk1jD/iBFjVbrtlNRIp9738DsOOJCALQ+Tph0
9dykXab5g869cwGdiwQayoNEnDfKBXvK3oo8Ojjrebf2r5/+P26ZRfTQ+QgjCgLZ
9l7To93JOgnBiiVAe5FWlvIuvyrGMVWxNJTHw9aXk0hOjsSByiGdE5kKjpHRhjzQ
f6ZFh+XS5YGLutqx5uPMaWXa7i7UMyJ4XPAqJvUeQrRUIBTeay72r9G9m2/cB8ND
/XT1c8MBAgMBAAECggEAAJaTHdpH9VVvQ6eOOmAalHyc9YEG1D1Q325TjkwyGbD0
u8GbTJ2PZsTFTeW7wxVsNp+RAJRZu75f7leQc9l4rbnJcp66Z1EozIGlhJp6SikN
DsMtC/20zpe2ooJLTfMV2zQTBRDhhPFlnptOuKQTwaZq2Qm7ZqaEQE/SJ5Cse1yc
RCWIo8rz45j6UgZuJuH0ygc0Xp7vO5hPABnCjObQVnWlz2fpkVTWL+sDueQWaAZN
Rwkmz9TxqvrBewKncLZM52lq2eh8FNnZDgSqddsAAojQiyeW8SEqMGqF88QB7LHn
Qs2AxTtEd/MCSTyRNVkoRWQOQAZmUnI5sD0uvh/tqQKBgQDruFru3H96w4RsN8Z3
GXjYxDz97nlZ2+n8f9P2MXrNx3scRFOiXyfDUkImgGUyBTCJ4IERKRAoNJlsiUuX
VcOco/d3Va/6ly7xTW26u2E1uJCikZgCJwrfkjT6DXihyrU16SZpyUEizGA3418r
ks27iTSDC3B+LGSl3xenrzsd2QKBgQC1P9+IDzBUpC9abZXPWk6G/2iPx8+Pi0Uo
1s/O5KU1f5Y6w7TWH7HTxm2hCAd1WJ2Y0UA4oYyj2+XelDUgoTcviKwedspDnoC9
oECFoaLxrb3wVDxJRH/yoDbW01mv1h3oOuhqk4HSMMUorqamKvwN7tnxQZZ8CjEc
YXdZDmSNaQKBgQCRBG81KoRnA2gxp7K6zPny6e/YDWGs7cWrKP0/Jju147aSslp9
t2rgGHhH9Y/MUTMGcA8XfprJEWseQe02YnYgpSN20EmesmoX3BnY2rS4dx7MVSQe
luRynSFogOcpKmuHijOuuzkObov3djOzu/JEIOokgOIpTahx/6ku6XhGKQKBgF/R
58PPe9aTgjFFU/juHivCZS32DWYu0542imAvgqPY0rw4cKbu1J1H/vct/ntsIM0E
evY1edu6yji0k62vbTRlBdGJLX84EYmuac372N/54Ttn3QNDAn1rc/J4g6axBNic
I/kMCIrtm53ZT2LzwJNBHdksunjTDomZgXYEZOZZAoGAMn1fWQnqAK8M9X0tHOV1
dVZ8Z7TFB54j+ok+m4jRD+PpuSLxZ5n4abWh5rbj/COrUedQaWUurnJMtH9E+cLU
kUI8Dg1HmZxhjfQPW1DGaTPDHnGLqkGKhSPTX4S442m4JjEVaQfCWAW/BJjazB1g
mS8Cd37KqsyeRA5oQmuOJtY=
-----END PRIVATE KEY-----',
        'chave_publica'      => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApuQofCHRE/hZpzAqe+ST
ESTgIFkJaUJuY0BaPvSqOjH1UWy1BGqH3A8LIZjS04LA9V19UlNOb8kb3aLWbLMj
xMayZVTXn4htWxK6+lCf3ZNYw/4gRY1W67ZTUSKfe9/A7DjiQgC0Pk6YdPXcpF2m
+YPOvXMBnYsEGsqDRJw3ygV7yt6KPDo463m39q+f/j9umUX00PkIIwoC2fZe06Pd
yToJwYolQHuRVpbyLr8qxjFVsTSUx8PWl5NITo7EgcohnROZCo6R0YY80H+mRYfl
0uWBi7rasebjzGll2u4u1DMieFzwKib1HkK0VCAU3msu9q/RvZtv3AfDQ/109XPD
AQIDAQAB
-----END PUBLIC KEY-----',
        'chave_publica_fake' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEApuQofCHRE/hZpzAqe+ST
ESTgIFkJaUJuY0BaPvSqOjH1UWy1BGqH3A8LIZjS04LA9V19UlNOb8kb3aLWbLMj
xMayZVTXn4htWxK6+lCf3ZNYw/4gRY1W67ZTUSKfe9/A7DjiQgC0Pk6YdPXcpF2m
+YPOvXMBnYsEGsqDRJw3ygV7yt6KPDo463m39q+f/j9umUX00PkIIwoC2fZe06Pd
yToJwYolQHuRVpbyLr8qxjFVsTSUx8PWl5NITo7EgcohnROZCo6R0YY80H+mRYfl
0uWBi7rasebjzGll2u4u1DMieFzwKib1HkK0VCAU3msu9q/RvZtv3AfDQ/109XPD
AQIDAQAB
-----END PUBLIC KEY-----',
        'secret_id'          => '40481-vrohdBhHtCSSJoOc#HBNJhRnqmFHMsKC6Kd4nqEHaFfudpoOul4Eb6noike%KYi$83Q7nA0X*f',
        'secret_id_fake'     => '40481-vrohdBhHtCSSJoOc#HBNJhRnqmFHMsKC6Kd4nqEHaFfudpoOul4Eb6noike%KYi$83Q7nA0X*f',
        'client_id'          => '6860808380-b2EssSnv9di##gNR#HnGFhz0GQAHc$V4psCVlMvxjJr5wBAn81Qx1inDIXhuJopGddpuzUHe1!kB*3.painel.yh',
        'client_id_fake'     => '6860808380-b2EssSnv9di##gNR#HnGFhz0GQAHc$V4psCVlMvxjJr5wBAn81Qx1inDIXhuJopGddpuzUHe1!kB*3.painel.yh',
        'audience'           => 'painel',
        'authorization_code' => 1,
        'client_credentials' => null,
        'refresh_token'      => 1,
        'redirect_uri'       => '["painel.yh"]',
        'scope_permitido'    => array_values(arrayRemoverValorDuplicado(Scope::PAINEL_INTERNO)),
        'campo_permitido'    => ConfiguracoesPadrao::CAMPOS_OBRIGATORIOS,
        'tempo_vida'         => '300',
        'data_criacao'       => '2023-07-04 17:22:05',
        'data_atualizacao'   => '2023-07-04 17:22:05',
        'status'             => '1'
    ],
    [
        'id'                 => '2',
        'uuid'               => '78c3b431-3540-482e-8061-6aac1b2992ea',
        'id_admin_empresa'   => '1',
        'nome'               => 'Painel - Login',
        'descricao'          => 'App para a pagina de login do painel',
        'imagem_app'         => null,
        'chave_privada'      => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDm+vb72T9qBhe+
OkCUItreCWlc4QN2OgZKv0JIwCWUzwJw2Qj2Mjh7sP43yUGrZOkOs2dnODjDezR+
ndOoMGrDT8Czu6Ta4cgxIHyLRBEj2iicL6Gsz25ZpDr1PliJZGS8ImXpaPa72ZUg
RRWb7E7A+/Q2Ldlfu6RJg1tcYdTx0+NjTsl3Aun6i3+aJJ1qs1DYhWQm3cZMgdWR
rXUoj/AieLVOqdSJZdVnZnI/9njskLJi3ruX7Z/kekPUaligi6bXVLTVRr8TUqtD
XiQiZR4S/LudYYpGbW4RiKAFl7aae8MYwB/3Ob9OHgTkf21ZoEgm13owujlXvHlz
tm+c/b6JAgMBAAECggEAD30tUkqpcrfpYC+CVJy4vhJ1/w00wPkXomwMaxn9eyrd
kEkxjWblPEAsb/u+PFrWOQ7lRHnz7oHkzYfSYXEru6CtvK+82JOy675ws5sTJisT
NZQJHLBFR1wYuwjdAYuaqLYKzPnlr8ehtSoEzrGsP0s1/2sYLFAGLorRNdL2p2es
NVWAYaBr5leN5pAi6SssTc7KePlUaRkP7Aqr2yuS7bWZhPJ2SCWsukGlL1QUKphp
+Ozq7tg+758yKG1AJ+Ng/0waCyOaGY/DhjVQc+m6VE8xnSwjtLK5W+l5P0SjhLlz
465lesMunsN5sfTEoLKtByThXmkqbke+d6W8WPI3bQKBgQD4XFCO4MNn7jAXEgB3
tJUmUgrcPp4j6S8eXm6x9zfTG8bDGYtG+z2+2+nr+1X7UuwbkH+6BUqYlyhoStxS
NugLRaU373WRz5G9RiXVKxukDzvkmn5t4tkpSzkliz85juSJg+lBsHKpf+KM1RIZ
5sEYSbGwqchIfjuJUG9Z3hqAzQKBgQDuFcqP3AA+2sX3CW2H64AS2OYTXCpokC2f
eKVr/DVgEkzO+i9FteLzd46sVjuKmDnET5L9N8EdEQ0TWXlA7r3IpTVfjd0auzD8
UhKJpBQ8QGQ9Q8OtRPybpgjPcB1Yv4QkF+rTFT11baVjCRgoW/nWnmbWsIUbgd5y
NGu9aVeErQKBgFhVFP3MKRO03nvGFk589rM7aMtupfYlwHFvqrU3NS6raWUIl8W2
I/7W3nrk2G7bzkf7e8Igkah10pRNU9EV4C/qJFohm9IxXidQZLJNc7ZFCMtEu4S/
svcB3yBgRytkjBnwxaxsYkuwxVXLjnWR4cmOpFkt+aL26pvq9L+kxA5dAoGBAKx3
GJ8RbRQbAmCrca6OB96fRDst/oJ0PrhQevQ4ZKnNV9pvHSNQWLCpIK7yYHtLPj1Y
r0/CvZ9CpMgpfk5tIwlfs8QeTdZ5V0jQYyFVvFrRAkvm9K5lVN+jbKpnurp5MU28
DZ0Ou9B+ttmA0wEZb2fqovBOOn3sm6j7FDjHQGCZAoGAIzx8BnaHRMEhJiXCjCSA
gMF6BHsZRYixJOf89QZCbt/aguwkCiOz5m5adGHAqqTdcZTjI0rwP+Ey/FkWMVJE
KxaHV8548vJ5Y41znr8fk1o5d4F2nxhUtznthd611qvBGOzNJlVbqQMTyZOOFh0X
bLCG707MYsi2uEDw5JfrmjE=
-----END PRIVATE KEY-----',
        'chave_privada_fake' => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDm+vb72T9qBhe+
OkCUItreCWlc4QN2OgZKv0JIwCWUzwJw2Qj2Mjh7sP43yUGrZOkOs2dnODjDezR+
ndOoMGrDT8Czu6Ta4cgxIHyLRBEj2iicL6Gsz25ZpDr1PliJZGS8ImXpaPa72ZUg
RRWb7E7A+/Q2Ldlfu6RJg1tcYdTx0+NjTsl3Aun6i3+aJJ1qs1DYhWQm3cZMgdWR
rXUoj/AieLVOqdSJZdVnZnI/9njskLJi3ruX7Z/kekPUaligi6bXVLTVRr8TUqtD
XiQiZR4S/LudYYpGbW4RiKAFl7aae8MYwB/3Ob9OHgTkf21ZoEgm13owujlXvHlz
tm+c/b6JAgMBAAECggEAD30tUkqpcrfpYC+CVJy4vhJ1/w00wPkXomwMaxn9eyrd
kEkxjWblPEAsb/u+PFrWOQ7lRHnz7oHkzYfSYXEru6CtvK+82JOy675ws5sTJisT
NZQJHLBFR1wYuwjdAYuaqLYKzPnlr8ehtSoEzrGsP0s1/2sYLFAGLorRNdL2p2es
NVWAYaBr5leN5pAi6SssTc7KePlUaRkP7Aqr2yuS7bWZhPJ2SCWsukGlL1QUKphp
+Ozq7tg+758yKG1AJ+Ng/0waCyOaGY/DhjVQc+m6VE8xnSwjtLK5W+l5P0SjhLlz
465lesMunsN5sfTEoLKtByThXmkqbke+d6W8WPI3bQKBgQD4XFCO4MNn7jAXEgB3
tJUmUgrcPp4j6S8eXm6x9zfTG8bDGYtG+z2+2+nr+1X7UuwbkH+6BUqYlyhoStxS
NugLRaU373WRz5G9RiXVKxukDzvkmn5t4tkpSzkliz85juSJg+lBsHKpf+KM1RIZ
5sEYSbGwqchIfjuJUG9Z3hqAzQKBgQDuFcqP3AA+2sX3CW2H64AS2OYTXCpokC2f
eKVr/DVgEkzO+i9FteLzd46sVjuKmDnET5L9N8EdEQ0TWXlA7r3IpTVfjd0auzD8
UhKJpBQ8QGQ9Q8OtRPybpgjPcB1Yv4QkF+rTFT11baVjCRgoW/nWnmbWsIUbgd5y
NGu9aVeErQKBgFhVFP3MKRO03nvGFk589rM7aMtupfYlwHFvqrU3NS6raWUIl8W2
I/7W3nrk2G7bzkf7e8Igkah10pRNU9EV4C/qJFohm9IxXidQZLJNc7ZFCMtEu4S/
svcB3yBgRytkjBnwxaxsYkuwxVXLjnWR4cmOpFkt+aL26pvq9L+kxA5dAoGBAKx3
GJ8RbRQbAmCrca6OB96fRDst/oJ0PrhQevQ4ZKnNV9pvHSNQWLCpIK7yYHtLPj1Y
r0/CvZ9CpMgpfk5tIwlfs8QeTdZ5V0jQYyFVvFrRAkvm9K5lVN+jbKpnurp5MU28
DZ0Ou9B+ttmA0wEZb2fqovBOOn3sm6j7FDjHQGCZAoGAIzx8BnaHRMEhJiXCjCSA
gMF6BHsZRYixJOf89QZCbt/aguwkCiOz5m5adGHAqqTdcZTjI0rwP+Ey/FkWMVJE
KxaHV8548vJ5Y41znr8fk1o5d4F2nxhUtznthd611qvBGOzNJlVbqQMTyZOOFh0X
bLCG707MYsi2uEDw5JfrmjE=
-----END PRIVATE KEY-----',
        'chave_publica'      => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5vr2+9k/agYXvjpAlCLa
3glpXOEDdjoGSr9CSMAllM8CcNkI9jI4e7D+N8lBq2TpDrNnZzg4w3s0fp3TqDBq
w0/As7uk2uHIMSB8i0QRI9oonC+hrM9uWaQ69T5YiWRkvCJl6Wj2u9mVIEUVm+xO
wPv0Ni3ZX7ukSYNbXGHU8dPjY07JdwLp+ot/miSdarNQ2IVkJt3GTIHVka11KI/w
Ini1TqnUiWXVZ2ZyP/Z47JCyYt67l+2f5HpD1GpYoIum11S01Ua/E1KrQ14kImUe
Evy7nWGKRm1uEYigBZe2mnvDGMAf9zm/Th4E5H9tWaBIJtd6MLo5V7x5c7ZvnP2+
iQIDAQAB
-----END PUBLIC KEY-----',
        'chave_publica_fake' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA5vr2+9k/agYXvjpAlCLa
3glpXOEDdjoGSr9CSMAllM8CcNkI9jI4e7D+N8lBq2TpDrNnZzg4w3s0fp3TqDBq
w0/As7uk2uHIMSB8i0QRI9oonC+hrM9uWaQ69T5YiWRkvCJl6Wj2u9mVIEUVm+xO
wPv0Ni3ZX7ukSYNbXGHU8dPjY07JdwLp+ot/miSdarNQ2IVkJt3GTIHVka11KI/w
Ini1TqnUiWXVZ2ZyP/Z47JCyYt67l+2f5HpD1GpYoIum11S01Ua/E1KrQ14kImUe
Evy7nWGKRm1uEYigBZe2mnvDGMAf9zm/Th4E5H9tWaBIJtd6MLo5V7x5c7ZvnP2+
iQIDAQAB
-----END PUBLIC KEY-----',
        'secret_id'          => '17013-OSaJ6Jlu02ieG6tN3!5Ju!Rd0bZt3Y9l6MWVg3p1AHwBkQLfVzi!uMR09lVx4WPXJq0GaqNqF%',
        'secret_id_fake'     => '17013-OSaJ6Jlu02ieG6tN3!5Ju!Rd0bZt3Y9l6MWVg3p1AHwBkQLfVzi!uMR09lVx4WPXJq0GaqNqF%',
        'client_id'          => '4232351400-VUaiVStugHvqqHHh9aXpw1df4stvlb0UELvmCY%4VYGWvoA8H4d*2#hWbSbwf7J8Z$sSaULyD99HYq.painel.yh',
        'client_id_fake'     => '4232351400-VUaiVStugHvqqHHh9aXpw1df4stvlb0UELvmCY%4VYGWvoA8H4d*2#hWbSbwf7J8Z$sSaULyD99HYq.painel.yh',
        'audience'           => 'login',
        'authorization_code' => null,
        'client_credentials' => 1,
        'refresh_token'      => null,
        'redirect_uri'       => '["painel.yh"]',
        'scope_permitido'    => array_values(arrayRemoverValorDuplicado(Scope::PAINEL_LOGIN)),
        'campo_permitido'    => '',
        'tempo_vida'         => '300',
        'data_criacao'       => '2023-07-04 17:22:05',
        'data_atualizacao'   => '2023-07-04 17:22:05',
        'status'             => '1'
    ],
    [
        'id'                 => '3',
        'uuid'               => '5add7e1c-3da1-4c0f-90b4-da17d4f05eca',
        'id_admin_empresa'   => '1',
        'nome'               => 'Clube - Interno',
        'descricao'          => 'App para integração do clube de vantagens',
        'imagem_app'         => null,
        'chave_privada'      => '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCDr11DEDaULsk0
O9Zzl0f9651n5HefRSkTBNXQQZMZJ0ivZCwVEmyZQLe5cqdZvs4JFsU9vMFUBAMm
a20U8fgZvraXDnoYeKViErPfDT5OMRhITssBOOry1mXLsiM+ojEZmMTrgrd19QuK
TceccU0RsGV2nMTJTNGdKKELBkNebtNxoFsPQY6zslaFbw//dfJKW+uH6isNkf1H
wxCL6B1kVetD/J+zcOLJVAMJgKSft8wCCpJFjwDV6/rfT5pB41+jN11uF5birZIl
XdWb+yR4YI8XxilrhnSl+AmH+woR8xoATSN4hjA+yLYc87l9X8HdLK8kuNUEq6Vc
uCNAHHNhAgMBAAECggEABnMudDx1D+kCveY4aMz+H7fMq+wK1+hBTMct2dd8Zvz/
mGelqRqP6ym6VgEBpWmtGdDdtFpsdbsElHsqip4XcttegiOZY111bEpSORii9p18
/P2u/85T47UQo1uEUmsLUJ+k9YwfhE2OVSXeIxGBnt4u+hJPqTR0B+iXiryxzAw7
nou8lG3JKacqmSFFl8PqlXTnq/iKURXEaYi+B64xgUJ9wTF74aQpXSaObBDXvuEF
u7lHhAtxZqAohH1xFltggdNAm5E3Hrl9gSe29YzMFPkjuygkRJoAEz1sOqOnhz5O
n29HMaBhhSihLap4MojZIdYapRhoceRjoqWK0DM7gQKBgQC4KXbb+3YtxnqX3ScO
Fe4q5NUTTgjWdYAKUxCdOAag9hQe54g7K7I18iDBYz4iKq4/EKsQz2FOINsGSdCn
+pyLK+QXsNcI7lLiIAQoGPE0FxczqpZ7LFvCCKphY5c6U8r/+/VsUWYNP7fR+DLz
A7tFiAiqI6IQdVxxT/4ouX5XeQKBgQC3DYRSkC0vhqHLEyheVyWoWAKHISP6qj8g
ydvL0Tsw3t2M55nKM9f0q9J5Gzuqg6ZzhcnLXWEaDw9twOIwqNrHSoZHRNe1CYTv
7fdBloR3LVnjngD+GQZHAvIlL7lKfMYtp//HqzoLRG/tXv/KEEnBgG0xQAIRvBAU
ERk+fnq5KQKBgBl1gfz/UmMZoTEFXbTQy1Aaumoky4v6Sm0i9pUFfcUav8VV9LtZ
WxWiF20krx5CEDyfrUZxpN4MJLtF0RanyqRiuPGdfNy7NbIVAv4YFKBC4O4/kvYM
N9MnJ4a3IdzqYJHq3w7OYfFK8bqROVnnFiiSmcALlQf5cYIosNYobpLhAoGBAI+n
LOu6N6uvZTNAApMeou/cuYcgS+MATfBGWZY2ydPNYmKcsoULcHOTj+X4qDdlf2/z
U2ykCHrxzLLeBuQW7zIs2vn9Hab2JHxB7KNtIuGAFqXZLoA6VUHPHwZt0GSTTNcx
mzovtqp297t5rRT++VLAnXBkAfu28Ys96+iuS1MZAoGBAInxmCRXxt25EkZPtaRG
oJ+8kvIwod6D641E3fnfzUL7YU/3K/PvfsQMBSrJbsSDKf/m+6vEpOERSBunkzNJ
8N92NPBYOnEo9/IX+M/02S5gmdPYc+u0OoO5ovjLTqAlywTDY6q3EG7gUQcJjOsa
MSqId9XfzxcFOezvsgQ2LxHt
-----END PRIVATE KEY-----',
        'chave_privada_fake' => '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCDr11DEDaULsk0
O9Zzl0f9651n5HefRSkTBNXQQZMZJ0ivZCwVEmyZQLe5cqdZvs4JFsU9vMFUBAMm
a20U8fgZvraXDnoYeKViErPfDT5OMRhITssBOOry1mXLsiM+ojEZmMTrgrd19QuK
TceccU0RsGV2nMTJTNGdKKELBkNebtNxoFsPQY6zslaFbw//dfJKW+uH6isNkf1H
wxCL6B1kVetD/J+zcOLJVAMJgKSft8wCCpJFjwDV6/rfT5pB41+jN11uF5birZIl
XdWb+yR4YI8XxilrhnSl+AmH+woR8xoATSN4hjA+yLYc87l9X8HdLK8kuNUEq6Vc
uCNAHHNhAgMBAAECggEABnMudDx1D+kCveY4aMz+H7fMq+wK1+hBTMct2dd8Zvz/
mGelqRqP6ym6VgEBpWmtGdDdtFpsdbsElHsqip4XcttegiOZY111bEpSORii9p18
/P2u/85T47UQo1uEUmsLUJ+k9YwfhE2OVSXeIxGBnt4u+hJPqTR0B+iXiryxzAw7
nou8lG3JKacqmSFFl8PqlXTnq/iKURXEaYi+B64xgUJ9wTF74aQpXSaObBDXvuEF
u7lHhAtxZqAohH1xFltggdNAm5E3Hrl9gSe29YzMFPkjuygkRJoAEz1sOqOnhz5O
n29HMaBhhSihLap4MojZIdYapRhoceRjoqWK0DM7gQKBgQC4KXbb+3YtxnqX3ScO
Fe4q5NUTTgjWdYAKUxCdOAag9hQe54g7K7I18iDBYz4iKq4/EKsQz2FOINsGSdCn
+pyLK+QXsNcI7lLiIAQoGPE0FxczqpZ7LFvCCKphY5c6U8r/+/VsUWYNP7fR+DLz
A7tFiAiqI6IQdVxxT/4ouX5XeQKBgQC3DYRSkC0vhqHLEyheVyWoWAKHISP6qj8g
ydvL0Tsw3t2M55nKM9f0q9J5Gzuqg6ZzhcnLXWEaDw9twOIwqNrHSoZHRNe1CYTv
7fdBloR3LVnjngD+GQZHAvIlL7lKfMYtp//HqzoLRG/tXv/KEEnBgG0xQAIRvBAU
ERk+fnq5KQKBgBl1gfz/UmMZoTEFXbTQy1Aaumoky4v6Sm0i9pUFfcUav8VV9LtZ
WxWiF20krx5CEDyfrUZxpN4MJLtF0RanyqRiuPGdfNy7NbIVAv4YFKBC4O4/kvYM
N9MnJ4a3IdzqYJHq3w7OYfFK8bqROVnnFiiSmcALlQf5cYIosNYobpLhAoGBAI+n
LOu6N6uvZTNAApMeou/cuYcgS+MATfBGWZY2ydPNYmKcsoULcHOTj+X4qDdlf2/z
U2ykCHrxzLLeBuQW7zIs2vn9Hab2JHxB7KNtIuGAFqXZLoA6VUHPHwZt0GSTTNcx
mzovtqp297t5rRT++VLAnXBkAfu28Ys96+iuS1MZAoGBAInxmCRXxt25EkZPtaRG
oJ+8kvIwod6D641E3fnfzUL7YU/3K/PvfsQMBSrJbsSDKf/m+6vEpOERSBunkzNJ
8N92NPBYOnEo9/IX+M/02S5gmdPYc+u0OoO5ovjLTqAlywTDY6q3EG7gUQcJjOsa
MSqId9XfzxcFOezvsgQ2LxHt
-----END PRIVATE KEY-----',
        'chave_publica'      => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAg69dQxA2lC7JNDvWc5dH
/eudZ+R3n0UpEwTV0EGTGSdIr2QsFRJsmUC3uXKnWb7OCRbFPbzBVAQDJmttFPH4
Gb62lw56GHilYhKz3w0+TjEYSE7LATjq8tZly7IjPqIxGZjE64K3dfULik3HnHFN
EbBldpzEyUzRnSihCwZDXm7TcaBbD0GOs7JWhW8P/3XySlvrh+orDZH9R8MQi+gd
ZFXrQ/yfs3DiyVQDCYCkn7fMAgqSRY8A1ev630+aQeNfozddbheW4q2SJV3Vm/sk
eGCPF8Ypa4Z0pfgJh/sKEfMaAE0jeIYwPsi2HPO5fV/B3SyvJLjVBKulXLgjQBxz
YQIDAQAB
-----END PUBLIC KEY-----',
        'chave_publica_fake' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAg69dQxA2lC7JNDvWc5dH
/eudZ+R3n0UpEwTV0EGTGSdIr2QsFRJsmUC3uXKnWb7OCRbFPbzBVAQDJmttFPH4
Gb62lw56GHilYhKz3w0+TjEYSE7LATjq8tZly7IjPqIxGZjE64K3dfULik3HnHFN
EbBldpzEyUzRnSihCwZDXm7TcaBbD0GOs7JWhW8P/3XySlvrh+orDZH9R8MQi+gd
ZFXrQ/yfs3DiyVQDCYCkn7fMAgqSRY8A1ev630+aQeNfozddbheW4q2SJV3Vm/sk
eGCPF8Ypa4Z0pfgJh/sKEfMaAE0jeIYwPsi2HPO5fV/B3SyvJLjVBKulXLgjQBxz
YQIDAQAB
-----END PUBLIC KEY-----',
        'secret_id'          => '29542-eAWQADHjlwDRP$t5rgEz%6DlGhedGdRL2Uwto6PcXBdT7O0*UnHd0NfbZ*9ZxWjUo6QSVfFXf0',
        'secret_id_fake'     => '29542-eAWQADHjlwDRP$t5rgEz%6DlGhedGdRL2Uwto6PcXBdT7O0*UnHd0NfbZ*9ZxWjUo6QSVfFXf0',
        'client_id'          => '8772171525-gb6EkbCfTMCR0tJsFDF1M#w9rgLoCk0G6knMT0QZec%MsH4YYg6cI10Uu7V*azdZldv2gY47X5HQIsO.clube.yh',
        'client_id_fake'     => '8772171525-gb6EkbCfTMCR0tJsFDF1M#w9rgLoCk0G6knMT0QZec%MsH4YYg6cI10Uu7V*azdZldv2gY47X5HQIsO.clube.yh',
        'audience'           => 'clube',
        'authorization_code' => 1,
        'client_credentials' => null,
        'refresh_token'      => 1,
        'redirect_uri'       => '["clube.yh"]',
        'scope_permitido'    => array_values(arrayRemoverValorDuplicado(Scope::CLUBE_INTERNO)),
        'campo_permitido'    => '',
        'tempo_vida'         => '86400',
        'data_criacao'       => date('Y-m-d H:i:s'),
        'data_atualizacao'   => date('Y-m-d H:i:s'),
        'status'             => '1'
    ],
    [
        'id'                 => '4',
        'uuid'               => '39b74f05-f063-4086-ad89-b9fa1cda7ced',
        'id_admin_empresa'   => '1',
        'nome'               => 'Clube - Login',
        'descricao'          => 'App para a pagina de login do clube de vantagens',
        'imagem_app'         => null,
        'chave_privada'      => '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDJ0SWDoFcefP1c
YWLjigl9qe3S29UCuo0JWaCda3dB20jgc9d3/MCpGHH9IKl3ApkA3e+/dKuif2wl
SRm84jTlf2VJEdb9tFl19EXtoLHlQCX5USabEs3MWD1XpEMwFlGPGveZHm/HEXZb
XmJ5msiO71UAebUh/ctbhZJ/xHjHAnkcHr8M+jl+O4aYF6jqwysYSOvrVSijAihi
4Elmp51azooDaxNLsIzRDAMv5NrHrHevof0s/rC0EkWk2vv99tvwJ+8/SBDF862n
c0CFI9P64+jLsaJQai5BHFqEGyQOmxkmxRu4oujxjHnoEARQviEbj82JZ7qyXcYC
+y46wAdtAgMBAAECggEARcblJIxUcrseUkltimRYgbU1//h3vnjHeJsfeC4GzqNF
oC0NK3QdHJnSF4WqcfZY9j7FvLlW+lj6CC4tEuxXA543Y7siTReE8s5hOxabSzsR
phDTuzwWIXfExb+KZ17gFg1p2+v1E6vK8y4/9/F3NEw1ZHOwF6+LFvqouzov1AY+
qHAoc1O5284Ft8PQx2TzjUuGnDNs8FB61KcbrPQ2EIUEXyxcuhAK+KYI2+UXoari
YC2uWmFNx/ro6leaXSfaTXIA7UgurDcCGnPjJyjIAy6/iwpCxCxkqBD1RMi+hCcw
3G3CHqbAWV6Z2KLRF8AJTFL3e2OKdpHyyM6Nk9lNNQKBgQDoUNKhYmkmQWu2Q3ro
Ge6uNRKbTUg1rUHjWMvRmEcSv+P8hOmmOJboBA0pld1tVMoh4xjsnEOfamgVh1Ez
Ddtntfs6kJZSYtpB3DvuVfMnMwUH3q9y26sb81G71xKNnkjq7xmt1PdsxFzD8yhA
mDRCiOCJMGOXYwm2snvtNTlPNwKBgQDeZFdesRVn4xLWq8rgZB/StxfEL+Uwpk99
9Pb86vNs/Cga6IdHj4bn3mq3jOyIUWcKTjCeUeU9rJTtKTQAw4XrrU733jZTVatu
mg8SKIw1eZQfjXyW5SLvl2bjnmzXmFpZShy4Aq8lRXo9p024Xw6y4TmUG25Lh38/
ckkQkkzIewKBgHkhIyHoiQDcXYgUGTKJyuxkWcZF/l+WbeNXejZXmr9I9FvHBXiY
yv5AlohXTrPh4N7YZTeBprSpK4oGGy1ujTa/KTX5C5f7WOe8KDd18yk+LeJTltFh
fg89yCET2+WhYU21y4LapwO8Qs4cq6E6ew0yBzkg1NyrU/ZBi+I7vRzzAoGBAJ7U
nym744Pcw1maPDzihW/i4BB3/IIwZVmaGMQtsUlHFgWRyPws37a+PQ12koARUzwi
98o6xzSzC3IOGVvqSL03e8y/YaFG8Db/xH+9gdW3TTjzveWvJLJlOVCblzSrVOus
aLJrCFfgNRvPX0ysZz0OaicDKFS9Iv5XSyOQuVH9AoGBAJzIY1yogrEjIcCpoSIn
A12+iWDVnCEM42Mh99LpsZK9M8nPuQKT9IE6TnMmm1g/W18sqlbU/xSZEDEZVyZ/
hU7RkJ9NYzmMyrpnuTCWAKqCfiIzSM///wx1jFnsz7ujTBh5TFQguAZRmIIQTW0z
T9PPdMGM4MVmnfLq9fDXJL83
-----END PRIVATE KEY-----',
        'chave_privada_fake' => '-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQDJ0SWDoFcefP1c
YWLjigl9qe3S29UCuo0JWaCda3dB20jgc9d3/MCpGHH9IKl3ApkA3e+/dKuif2wl
SRm84jTlf2VJEdb9tFl19EXtoLHlQCX5USabEs3MWD1XpEMwFlGPGveZHm/HEXZb
XmJ5msiO71UAebUh/ctbhZJ/xHjHAnkcHr8M+jl+O4aYF6jqwysYSOvrVSijAihi
4Elmp51azooDaxNLsIzRDAMv5NrHrHevof0s/rC0EkWk2vv99tvwJ+8/SBDF862n
c0CFI9P64+jLsaJQai5BHFqEGyQOmxkmxRu4oujxjHnoEARQviEbj82JZ7qyXcYC
+y46wAdtAgMBAAECggEARcblJIxUcrseUkltimRYgbU1//h3vnjHeJsfeC4GzqNF
oC0NK3QdHJnSF4WqcfZY9j7FvLlW+lj6CC4tEuxXA543Y7siTReE8s5hOxabSzsR
phDTuzwWIXfExb+KZ17gFg1p2+v1E6vK8y4/9/F3NEw1ZHOwF6+LFvqouzov1AY+
qHAoc1O5284Ft8PQx2TzjUuGnDNs8FB61KcbrPQ2EIUEXyxcuhAK+KYI2+UXoari
YC2uWmFNx/ro6leaXSfaTXIA7UgurDcCGnPjJyjIAy6/iwpCxCxkqBD1RMi+hCcw
3G3CHqbAWV6Z2KLRF8AJTFL3e2OKdpHyyM6Nk9lNNQKBgQDoUNKhYmkmQWu2Q3ro
Ge6uNRKbTUg1rUHjWMvRmEcSv+P8hOmmOJboBA0pld1tVMoh4xjsnEOfamgVh1Ez
Ddtntfs6kJZSYtpB3DvuVfMnMwUH3q9y26sb81G71xKNnkjq7xmt1PdsxFzD8yhA
mDRCiOCJMGOXYwm2snvtNTlPNwKBgQDeZFdesRVn4xLWq8rgZB/StxfEL+Uwpk99
9Pb86vNs/Cga6IdHj4bn3mq3jOyIUWcKTjCeUeU9rJTtKTQAw4XrrU733jZTVatu
mg8SKIw1eZQfjXyW5SLvl2bjnmzXmFpZShy4Aq8lRXo9p024Xw6y4TmUG25Lh38/
ckkQkkzIewKBgHkhIyHoiQDcXYgUGTKJyuxkWcZF/l+WbeNXejZXmr9I9FvHBXiY
yv5AlohXTrPh4N7YZTeBprSpK4oGGy1ujTa/KTX5C5f7WOe8KDd18yk+LeJTltFh
fg89yCET2+WhYU21y4LapwO8Qs4cq6E6ew0yBzkg1NyrU/ZBi+I7vRzzAoGBAJ7U
nym744Pcw1maPDzihW/i4BB3/IIwZVmaGMQtsUlHFgWRyPws37a+PQ12koARUzwi
98o6xzSzC3IOGVvqSL03e8y/YaFG8Db/xH+9gdW3TTjzveWvJLJlOVCblzSrVOus
aLJrCFfgNRvPX0ysZz0OaicDKFS9Iv5XSyOQuVH9AoGBAJzIY1yogrEjIcCpoSIn
A12+iWDVnCEM42Mh99LpsZK9M8nPuQKT9IE6TnMmm1g/W18sqlbU/xSZEDEZVyZ/
hU7RkJ9NYzmMyrpnuTCWAKqCfiIzSM///wx1jFnsz7ujTBh5TFQguAZRmIIQTW0z
T9PPdMGM4MVmnfLq9fDXJL83
-----END PRIVATE KEY-----',
        'chave_publica'      => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAydElg6BXHnz9XGFi44oJ
fant0tvVArqNCVmgnWt3QdtI4HPXd/zAqRhx/SCpdwKZAN3vv3Sron9sJUkZvOI0
5X9lSRHW/bRZdfRF7aCx5UAl+VEmmxLNzFg9V6RDMBZRjxr3mR5vxxF2W15ieZrI
ju9VAHm1If3LW4WSf8R4xwJ5HB6/DPo5fjuGmBeo6sMrGEjr61UoowIoYuBJZqed
Ws6KA2sTS7CM0QwDL+Tax6x3r6H9LP6wtBJFpNr7/fbb8CfvP0gQxfOtp3NAhSPT
+uPoy7GiUGouQRxahBskDpsZJsUbuKLo8Yx56BAEUL4hG4/NiWe6sl3GAvsuOsAH
bQIDAQAB
-----END PUBLIC KEY-----',
        'chave_publica_fake' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAydElg6BXHnz9XGFi44oJ
fant0tvVArqNCVmgnWt3QdtI4HPXd/zAqRhx/SCpdwKZAN3vv3Sron9sJUkZvOI0
5X9lSRHW/bRZdfRF7aCx5UAl+VEmmxLNzFg9V6RDMBZRjxr3mR5vxxF2W15ieZrI
ju9VAHm1If3LW4WSf8R4xwJ5HB6/DPo5fjuGmBeo6sMrGEjr61UoowIoYuBJZqed
Ws6KA2sTS7CM0QwDL+Tax6x3r6H9LP6wtBJFpNr7/fbb8CfvP0gQxfOtp3NAhSPT
+uPoy7GiUGouQRxahBskDpsZJsUbuKLo8Yx56BAEUL4hG4/NiWe6sl3GAvsuOsAH
bQIDAQAB
-----END PUBLIC KEY-----',
        'secret_id'          => '38139-0J2hHKWBslSyQAPA*5H2fZX1WdiGurkJ#I*IJBrNX!ESOgDs9ZFK2Ha#EnYRK41oP0H6EeVD28',
        'secret_id_fake'     => '38139-0J2hHKWBslSyQAPA*5H2fZX1WdiGurkJ#I*IJBrNX!ESOgDs9ZFK2Ha#EnYRK41oP0H6EeVD28',
        'client_id'          => '9616240871-ylo9eso7vSHp!QBLLvAF%vfFFadR0hvFoxNzMm%m$7FSQZLU$rimirKewr6V3hhQFQYG$zCP4inT6gY.clube.yh',
        'client_id_fake'     => '9616240871-ylo9eso7vSHp!QBLLvAF%vfFFadR0hvFoxNzMm%m$7FSQZLU$rimirKewr6V3hhQFQYG$zCP4inT6gY.clube.yh',
        'audience'           => 'login',
        'authorization_code' => null,
        'client_credentials' => 1,
        'refresh_token'      => null,
        'redirect_uri'       => '["clube.yh"]',
        'scope_permitido'    => array_values(arrayRemoverValorDuplicado(Scope::CLUBE_LOGIN)),
        'campo_permitido'    => '',
        'tempo_vida'         => '300',
        'data_criacao'       => date('Y-m-d H:i:s'),
        'data_atualizacao'   => date('Y-m-d H:i:s'),
        'status'             => '1'
    ],
    [
        'id'                 => '5',
        'uuid'               => 'b4bd65c1-0df1-4181-8fb1-ccf179466a6c',
        'id_admin_empresa'   => '1',
        'nome'               => 'Sistema',
        'descricao'          => 'App com todas as permissões para testes do sistema',
        'imagem_app'         => null,
        'chave_privada'      => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCw1IJwLoPH2e8T
qb11h2t4c0+VRhqf3WoikM1IjKvJpZMhgWrW6QmGFrma/Jn/cREKNSw5oTr0qQnO
Ea6WxfR9opeWsp16iSgkAvpvLYRnsDPlOwEqJt7YIY4Pif4gNucLUoH9YVX0b4DA
WbGV5qtNqqGu101bJIQ95aYjTihXdx6qBIAsR20Vy/QXP92ggNN9omPhu/zlSBkb
7gtsi0C3f1niwVD+fy8ERG/yv0czYN59B2XqdTECJCKRFch0RkB9EMDC41ljpuC8
JNd5dqEhuPWuAeRDK+yURAR2OJz2/6sghGij69kxnkgpYdb1Qt+NEMFNvw8eDu2J
kxFHcjh9AgMBAAECggEANh9lKSbjiNjodxE9/uoK99v7YMoLPiEL6jZ9zpXXwqle
BiRRaiP7mUH9XzWvgOK/f2tU9PKnU9klOiBpWpaORaHtWd+jmay/T35l3n+FBiOF
TsUnjxpEnMxwacsnr/596xY+2SngoCc8tiL92s62Ydg3EoetkpbzrupKVoYGjPZT
Mc9J+gekPDKKp36pV24UZOoijOg7+r4KS/C602yFi6sUOWVnkc8Lf6ZrVvLlls2q
IIIZxow9kJSXuVdWVc6DSYbimqs736mJDTZ5Gx4N4B5uajAFWfIYvhLaPa4t0mJ8
qfwL+m89E3aTK6N5nwt2deIX+zG6U0xMMw9nIxE0VQKBgQDjVF23Gcw76zvBQnBA
c1Y066Dk30xzrufA8HrY7lvIgbbveeYE62WQPplNqUui6DGDTOhnK3vbk5NeFmTB
4CUOkPY6o0HLY+DAaS51DXfFTrVtzCnSYWDTu1Q48GZL5SXGM/2zBDVowcE8g4eT
7YAJFHKPgkT1mQRnG5TOwbn6LwKBgQDHIbPbWAPv8KADVFDL7HtEVzCvdHHL3tbR
GyOtkjE+cfzYQwikabKSs+4MDNpidOBYapUeq0tRT9t2Lt6d8DL/TmjomoJReztQ
VtknlHM9GMGRcjGDqKaMtvFmKEfqU1s6LuTlZmToI4yO2titDaS5leaFPoH67ZHP
/L7Dv3cJEwKBgGJ/b4uiVeewaNe33cNPNg7/+VqxlgKZyjjC+bd8r7AU5BqnCo8f
H4Z8WRhZPjh7uthVPm1VXfkp3DRsJ4QJhoTlMor9qchiokM8T7DQAREwpVPJnmVV
ZArZpAh3wRspUlCqvkYOkMWvDfcWVmw1/7/vaCPeIdr4TfwO608aiWZpAoGAFBM6
vZ827maUyyEh8B0MJEyh9N5JZqM1huk0hJgbbM8zDV5cra6WOSH4mrBlB83+nAtK
s0fTlQP2/Qgmm0eO7JwsCV8iSjKhPS00QUA/CebIBPC9OyeyercqyQDAl0gGB2yY
2OOkwp5q2jAH7hJVC/ynu8Ece36d15kEZlmUF48CgYEApjOMOhipeqO0uFWoV4LJ
h2Ik7Q2XGBAV8duqYIWjFNhR35BZj/FkVEPnVLYiesVnkCcgorcBvcEh60HTAvjD
T6wP50/AibeYMlKXZ7wvuXEUayyhv/6u/pWJAJLLEtRtf+tj2BLatmt01ECDukQP
p0j2JzwRLX1dzuih6TcANTA=
-----END PRIVATE KEY-----',
        'chave_privada_fake' => '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQCw1IJwLoPH2e8T
qb11h2t4c0+VRhqf3WoikM1IjKvJpZMhgWrW6QmGFrma/Jn/cREKNSw5oTr0qQnO
Ea6WxfR9opeWsp16iSgkAvpvLYRnsDPlOwEqJt7YIY4Pif4gNucLUoH9YVX0b4DA
WbGV5qtNqqGu101bJIQ95aYjTihXdx6qBIAsR20Vy/QXP92ggNN9omPhu/zlSBkb
7gtsi0C3f1niwVD+fy8ERG/yv0czYN59B2XqdTECJCKRFch0RkB9EMDC41ljpuC8
JNd5dqEhuPWuAeRDK+yURAR2OJz2/6sghGij69kxnkgpYdb1Qt+NEMFNvw8eDu2J
kxFHcjh9AgMBAAECggEANh9lKSbjiNjodxE9/uoK99v7YMoLPiEL6jZ9zpXXwqle
BiRRaiP7mUH9XzWvgOK/f2tU9PKnU9klOiBpWpaORaHtWd+jmay/T35l3n+FBiOF
TsUnjxpEnMxwacsnr/596xY+2SngoCc8tiL92s62Ydg3EoetkpbzrupKVoYGjPZT
Mc9J+gekPDKKp36pV24UZOoijOg7+r4KS/C602yFi6sUOWVnkc8Lf6ZrVvLlls2q
IIIZxow9kJSXuVdWVc6DSYbimqs736mJDTZ5Gx4N4B5uajAFWfIYvhLaPa4t0mJ8
qfwL+m89E3aTK6N5nwt2deIX+zG6U0xMMw9nIxE0VQKBgQDjVF23Gcw76zvBQnBA
c1Y066Dk30xzrufA8HrY7lvIgbbveeYE62WQPplNqUui6DGDTOhnK3vbk5NeFmTB
4CUOkPY6o0HLY+DAaS51DXfFTrVtzCnSYWDTu1Q48GZL5SXGM/2zBDVowcE8g4eT
7YAJFHKPgkT1mQRnG5TOwbn6LwKBgQDHIbPbWAPv8KADVFDL7HtEVzCvdHHL3tbR
GyOtkjE+cfzYQwikabKSs+4MDNpidOBYapUeq0tRT9t2Lt6d8DL/TmjomoJReztQ
VtknlHM9GMGRcjGDqKaMtvFmKEfqU1s6LuTlZmToI4yO2titDaS5leaFPoH67ZHP
/L7Dv3cJEwKBgGJ/b4uiVeewaNe33cNPNg7/+VqxlgKZyjjC+bd8r7AU5BqnCo8f
H4Z8WRhZPjh7uthVPm1VXfkp3DRsJ4QJhoTlMor9qchiokM8T7DQAREwpVPJnmVV
ZArZpAh3wRspUlCqvkYOkMWvDfcWVmw1/7/vaCPeIdr4TfwO608aiWZpAoGAFBM6
vZ827maUyyEh8B0MJEyh9N5JZqM1huk0hJgbbM8zDV5cra6WOSH4mrBlB83+nAtK
s0fTlQP2/Qgmm0eO7JwsCV8iSjKhPS00QUA/CebIBPC9OyeyercqyQDAl0gGB2yY
2OOkwp5q2jAH7hJVC/ynu8Ece36d15kEZlmUF48CgYEApjOMOhipeqO0uFWoV4LJ
h2Ik7Q2XGBAV8duqYIWjFNhR35BZj/FkVEPnVLYiesVnkCcgorcBvcEh60HTAvjD
T6wP50/AibeYMlKXZ7wvuXEUayyhv/6u/pWJAJLLEtRtf+tj2BLatmt01ECDukQP
p0j2JzwRLX1dzuih6TcANTA=
-----END PRIVATE KEY-----',
        'chave_publica'      => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsNSCcC6Dx9nvE6m9dYdr
eHNPlUYan91qIpDNSIyryaWTIYFq1ukJhha5mvyZ/3ERCjUsOaE69KkJzhGulsX0
faKXlrKdeokoJAL6by2EZ7Az5TsBKibe2CGOD4n+IDbnC1KB/WFV9G+AwFmxlear
TaqhrtdNWySEPeWmI04oV3ceqgSALEdtFcv0Fz/doIDTfaJj4bv85UgZG+4LbItA
t39Z4sFQ/n8vBERv8r9HM2DefQdl6nUxAiQikRXIdEZAfRDAwuNZY6bgvCTXeXah
Ibj1rgHkQyvslEQEdjic9v+rIIRoo+vZMZ5IKWHW9ULfjRDBTb8PHg7tiZMRR3I4
fQIDAQAB
-----END PUBLIC KEY-----',
        'chave_publica_fake' => '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAsNSCcC6Dx9nvE6m9dYdr
eHNPlUYan91qIpDNSIyryaWTIYFq1ukJhha5mvyZ/3ERCjUsOaE69KkJzhGulsX0
faKXlrKdeokoJAL6by2EZ7Az5TsBKibe2CGOD4n+IDbnC1KB/WFV9G+AwFmxlear
TaqhrtdNWySEPeWmI04oV3ceqgSALEdtFcv0Fz/doIDTfaJj4bv85UgZG+4LbItA
t39Z4sFQ/n8vBERv8r9HM2DefQdl6nUxAiQikRXIdEZAfRDAwuNZY6bgvCTXeXah
Ibj1rgHkQyvslEQEdjic9v+rIIRoo+vZMZ5IKWHW9ULfjRDBTb8PHg7tiZMRR3I4
fQIDAQAB
-----END PUBLIC KEY-----',
        'secret_id'          => '03628-1JRq9pVOOyaIqv6yXeR3*b9cqTIGqHAyi6rEM82KQdUNZyOg%%l%iP0vqfD2AcCgD$40!cACrE',
        'secret_id_fake'     => '03628-1JRq9pVOOyaIqv6yXeR3*b9cqTIGqHAyi6rEM82KQdUNZyOg%%l%iP0vqfD2AcCgD$40!cACrE',
        'client_id'          => '2693969353-oBAjwJb1RXDE9P!Y!BfQc9Hnf7oFtfOxm8tkPH%YYD0MO3erHSI1zhwyte1JqMiO4#HM6Thquy9Uv.sistema.yh',
        'client_id_fake'     => '2693969353-oBAjwJb1RXDE9P!Y!BfQc9Hnf7oFtfOxm8tkPH%YYD0MO3erHSI1zhwyte1JqMiO4#HM6Thquy9Uv.sistema.yh',
        'audience'           => 'sistema',
        'authorization_code' => 1,
        'client_credentials' => 1,
        'refresh_token'      => 1,
        'redirect_uri'       => '["sistema.yh","painel.yh","clube.yh"]',
        'scope_permitido'    => array_values(arrayRemoverValorDuplicado(Scope::TUDO)),
        'campo_permitido'    => ConfiguracoesPadrao::CAMPOS_PERMITIDOS,
        'tempo_vida'         => '5000',
        'data_criacao'       => date('Y-m-d H:i:s'),
        'data_atualizacao'   => date('Y-m-d H:i:s'),
        'status'             => '1'
    ],
];
