<li class="linha_resposta">
    <div class="botao_mostrar botao_mostrar_request"></div>
    <ul>
        <li class="pre">
            <span>Header</span>
            <pre><?php print_r($linha->header) ?></pre>
        </li>
        <li class="pre">
            <span>Parametros</span>
            <pre><?php print_r($linha->parametro) ?></pre>
        </li>
        <li class="pre">
            <span>Body</span>
            <pre><?php print_r($linha->body) ?></pre>
        </li>
        <li class="pre">
            <span>JSON</span>
            <pre><?php print_r($linha->json) ?></pre>
        </li>
        <li class="pre">
            <span>Resposta</span>
            <pre><?php echo htmlentities(print_r($linha->resposta, true)) ?></pre>
        </li>
    </ul>
</li>
