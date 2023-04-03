<?php

$Doc = new DocumentacaoConfig\Fw('ENTITY', 'Entidade de um registro');

$Doc
    ->paragrafo('Talvez a Entity seja a coisa mais difícil de se entender aqui, ela é uma entidade, um registro, as boas praticas dizem que ela não pode ser uma lista, mas nada impede que isso ocorra porque ela extende o \ORM\ORM, ou seja, tudo o que ele faz, a Entity faz também.')
    ->paragrafo('O grande trunfo da Entity são suas ações automáticas, elas são feitas principalmente por propriedades e métodos pré-definidos que vou listar agora:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Tipo', 'Propriedade', 'Descrição'])
            ->tr(['string', '_tabela', 'Nome da tabela que será usada'])
            ->tr(['array', '_buscar', 'Lista de campos do banco que poderão ser buscados'])
            ->tr(['array', '_salvar', 'Lista de campos do banco que poderão ser salvos tanto no insert como no update'])
            ->tr(['array', '_insert', 'Lista de campos do banco que poderão ser salvos apenas no insert'])
            ->tr(['array', '_update', 'Lista de campos do banco que poderão ser salvos apenas no update'])
            ->tr(['array', '_wherePadrao', 'Um where seguindo os mesmo critórios do método where para ser usado em qualquer buscar'])
            ->tr(['string', '_validarSalvar', 'Uma lista de validações para ser executada sempre que for fazer um insert ou update'])
            ->tr(['string', '_validarInsert', 'Uma lista de validações para ser executada sempre que for fazer um insert'])
            ->tr(['string', '_validarUpdate', 'Uma lista de validações para ser executada sempre que for fazer um update'])
            ->tr(['mixed', 'campo_do_banco', 'Todo campo do banco pode ser setado como uma propriedade public ou protected e setado seu tipo, podemos ser os tipos padrões do PHP ou uma StatusInterface ou um ModulesInterface']);
    })
    ->paragrafo('Todas as propriedades acima, menos os campos do banco devem user o nível de segurança protected, já os campos dos banco você pode proteger eles para ser acessado ou não diretamente da classe. Outro ponto são as propriedades de validação, elas são simplismente o método validar do Helper de validação.')
    ->paragrafo('Agora que vimos as propriedades, vermos os métodos padrões que todos devem ser declarados como protected:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->trTitulo(['Método', 'Descrição'])
            ->tr(['regraBuscar', 'Executa o método antes de fazer uma busca'])
            ->tr(['regraPosBuscar', 'Executa o método depois de fazer uma busca'])
            ->tr(['regraSalvar', 'Executa o método antes de fazer um insert ou update'])
            ->tr(['regraPosSalvar', 'Executa o método depois de fazer um insert ou update'])
            ->tr(['regraInsert', 'Executa o método antes de fazer um insert'])
            ->tr(['regraPosInsert', 'Executa o método depois de fazer um insert'])
            ->tr(['regraUpdate', 'Executa o método antes de fazer um update'])
            ->tr(['regraPosUpdate', 'Executa o método depois de fazer um update'])
            ->tr(['regraDestruir', 'Executa o método antes de destruir a entity'])
            ->tr(['regraPosDestruir', 'Executa o método depois de destruir a entity'])
            ->tr(['prop', 'Pega o valor de um valor do banco mesmo que ele esteja privado'])
            ->tr(['getNomeCampo', 'Esse é um método curinga para pegar algum falor de propriedades protegidas, para pegar ele, basta chamar o método get("nome_campo")']);
    })
    ->paragrafo('Como vimos, temos várias propriedades e métodos que fazem a mágia acontecer, para ficar mais claro, vamos a alguns exemplos:')
    ->codigo('
<?php

namespace App\Models\Exemplo;

use ORM\Entity;
use Modules\Cpf;
use Modules\Data;
use Modules\Nome;
use Modules\Email;
use Modules\Telefone;
use App\Classes\Exemplo\Status;

final class ExemploEntity extends Entity
{
    protected string $ormTabela = TABELA_EXEMPLO;

    protected array $ormBuscar = ["nome", "data_nascimento", "status", "email", "telefone", "!cpf", "permissao", "data_criacao"];
    protected array $ormInsert = ["cpf", "data_nascimento"];
    protected array $ormSalvar = ["nome", "status", "email", "telefone", "permissao"];

    protected string $ormValidarSalvar = "
        nome|Nome|vazio
        data_nascimento|Data de nascimento|vazio|date
        email|E-mail|vazio|email
        cpf|CPF|vazio|cpf
    ";

    public Nome $nome;
    public Data $data_nascimento;
    public Email $email;
    public Telefone $telefone;
    public Status $status;
    protected Cpf $cpf;
    protected array $permissao;

    protected function getId()
    {
        return $this->prop("id");
    }
}
    ')
    ->paragrafo('No arquivo acima, criamos várias propriedades de métodos citados acima, podemos ver por exemplo, que podemos fazer o insert no CPF e data de nascimento mas não podemos atualizá-la, outro ponto interessante é o método getId, ele retorna o ID real da entity, isso porque, a entidade usa o uuid como id principal, e ele sempre será público e disponível na classe. Com essa entidade criada, podemos usá-la, por exemplo diretamente no controller:')
    ->codigo('
<?php

namespace App\Controllers\Exemplo;

use App\Models\Exemplo\ExemploEntity;
use Controller\Controller;

final class ExemploController extends Controller
{
    public function view(string $id)
    {
        $Exemplo = new ExemploEntity();
        $Exemplo->uuid($id);

        return view("exemplo", [
            "id" => $Exemplo->get("id"),
            "nome" => $Exemplo->nome->primeiroNome(),
            "email" => $Exemplo->email->email(),
            "status" => $Exemplo->status->indice()
        ]);
    }
}
    ')
    ->paragrafo('No exemplo acima, vimos que podemos usar o métodos depois de instânciar uma Entity e podemos chamar ou o método get para executar os métodos mágicos getNomeCampo ou pegar as propriedades diretamente, desde que elas estajam como públicas. Para facilitar, irei listar os métodos que podem ser usados em uma Entity instânciada:')
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('id')
            ->paragrafo('Busca um registro pelo UUID, identico a ["id", $id] de uma where padrão')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', '$id', 'Uuid do item que deseja buscar.')
                    ->parametro('bool', '$erro', 'Se true, irá retornar erro 404 caso não ache o registro.');
            })
            ->codigo('id(string $id, bool $erro = true): self')
            ->retorno('self', '')
            ->throw('\Erro\Excecao', 'Retorna uma exceção de status 404 caso não ache o registro');
        $Doc
            ->titulo('buscar')
            ->paragrafo('Busca um registro passando um where')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('array', '$where', 'Where padrão do ORM.')
                    ->parametro('bool', '$erro', 'Se true, irá retornar erro 404 caso não ache o registro.');
            })
            ->codigo('buscar(array $where, bool $erro = true): self')
            ->retorno('self', '')
            ->throw('\Erro\Excecao', 'Retorna uma exceção de status 404 caso não ache o registro');
        $Doc
            ->titulo('salvar')
            ->paragrafo('Manda salvar uma Entity, caso ela já exista, irá atualizar seus dados.')
            ->codigo('salvar(): self')
            ->retorno('self', '')
            ->throw('\Erro\Excecao', 'Retorna uma exceção caso de algum erro ao salvar')
            ->paragrafo('Após fazer um insert ou update, o salvar sempre irá buscar o registro e executar qualquer regra que tenha definida.');
        $Doc
            ->titulo('destruir')
            ->paragrafo('Destroi uma entity, isso inclue em deletar o registro no banco de dados.')
            ->codigo('destruir(): self')
            ->retorno('self', '')
            ->throw('\Erro\Excecao', 'Retorna uma exceção caso de algum erro ao destruir');
        $Doc
            ->titulo('get')
            ->paragrafo('Pega um valor caso ele a propriedade exista e tiver pública ou um método getNomeCampoBuscado.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', '$propriedade', 'Propriedade que deseja buscar.');
            })
            ->codigo('get(string $propriedade): mixed')
            ->retorno('mixed', 'Retorna o valor da propriedade')
            ->throw('\Erro\Excecao', 'Retorna uma exceção caso a propriedade não exista');
        $Doc
            ->titulo('set')
            ->paragrafo('Seta uma propriedade ou uma lista de propriedades.')
            ->blocoParametro(function () use ($Doc) {
                $Doc
                    ->parametro('string', '$propriedade', 'Propriedade que deseja setar.')
                    ->parametro('mixed', '$valor', 'Valor da propriedade.')
                    ->parametro('null|array', '$lista', 'Array com a lista de propriedades a seta no padrão ["propriedade" => $valor].');
            })
            ->codigo('set(string $propriedade = "", $valor = "", ?array $lista = null): void')
            ->retorno('void', 'Não tem retorno')
            ->throw('\Erro\Excecao', 'Retorna uma exceção caso você não tenha permissão para setar alguma propriedade')
            ->paragrafo('Lembrando que caso passe o parâmetro $propriedade, você deve passar seu valor. No caso de passar uma lista, não deve passar uma propriedade.');
    })
    ->paragrafo('Para finalizar, vamos fazer alguns exemplos práticos, primeiro, iremos salvar um exemplo:')
    ->codigo('
...
public function postSalvar(Request $request)
{
    $Exemplo = new ExemploEntity(
        nome: new Nome($request->nome),
        email: new Email($request->email)
    );

    $Exemplo->salvar();
    return sucesso([
        "id" => $Exemplo->id,
        "nome" => $Exemplo->nome->nome(),
        "email" => $Exemplo->email->email()
    ], status: 201);
}
...
    ')
    ->paragrafo('Como foi visto, podemos usar o construtor para passar os parâmetros para salvar um novo registro, após fazer isso, basta salvar. Só tome cuidado porque com esse modo, você pode sobrescrever algo se não passar os dados novamente após um insert.')

    ->paragrafo('Depois de salvo, vamos buscar o registro e atualizar:')
    ->codigo('
...
public function putAtualizar(Request $request, string $id)
{
    $Exemplo = new ExemploEntity();
    $Exemplo->uuid($id);

    $Exemplo->nome = new Nome($request->nome);
    $Exemplo->email = new Nome($request->email);
    $Exemplo->salvar();

    return sucesso([
        "id" => $Exemplo->id,
        "nome" => $Exemplo->nome->nome(),
        "email" => $Exemplo->email->email()
    ], status: 201);
}
...
    ')
    ->paragrafo('Aqui por didatica, usei os parâmetros setando diretamente das propriedades da classe, ao buscar o registro, a entity entende que você não vai mais salvar os dados e sim atualizar, vou aproveitar e mostrar outra forma de fazer isso:')
    ->codigo('
...
public function putAtualizar(Request $request, string $id)
{
    $Exemplo = new ExemploEntity();
    $Exemplo->uuid($id);
    $Exemplo->set(lista: $request->dado());
    $Exemplo->salvar();

    return sucesso([
        "id" => $Exemplo->id,
        "nome" => $Exemplo->nome->nome(),
        "email" => $Exemplo->email->email()
    ], status: 201);
}
...
    ')
    ->paragrafo('Única mudança foi o uso do set para setar os valores em massa.')
    ->paragrafo('O proxímo passa é buscar o registro, nada de novo já que fizemos isso para atualizar os dados:')
    ->codigo('
...
public function getBuscar(string $id)
{
    $Exemplo = new ExemploEntity();
    $Exemplo->uuid($id);

    return sucesso([
        "id" => $Exemplo->id,
        "nome" => $Exemplo->nome->nome(),
        "email" => $Exemplo->email->email()
    ], status: 201);
}
...
    ')
    ->paragrafo('Por fim, vamos deletar um registro:')
    ->codigo('
...
public function deleteDeletar(string $id)
{
    $Exemplo = new ExemploEntity();
    $Exemplo->uuid($id);
    $Exemplo->destruir();

    return new Response(status: 204);
}
...
    ')
    ->paragrafo('');

echo $Doc;
