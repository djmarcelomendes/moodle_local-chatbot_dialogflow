Webchat flutuante para Moodle com Dialogflow
==================
Autor: Marcelo Mendes do Amaral

Adaptação do projeto:https://github.com/aravindmohanoor/rich-webchat-demo para funcionamento no Moodle

Com esse plugin é possível usar todas as intents criadas no projeto do Dialogflow, deixando o moodle com um robô de respostas automáticas, para responder dúvidas préviamente mapeadas no Dialogflow. É necessário criar uma chave no painel do google para que a api do plugin tenha acesso ao projeto do Dialogflow configurado. Esse arquivo é no formato json e é inserido na tela de configuração do plugin na instalação. Nessa mesma tela é possível alterar as cores do botão do chat e seu cabeçalho.

Foi criada a opção de armazenar o histórico de conversa, para que ao atualizar a página, a conversa não seja limpa. Com essa opção ativada é armazenada em uma nova tabela. Se estiver desativada, não armazena nada no banco de dados. Com isso foi colocada a opção de excluir os dados depois de um tempo em minutos, por exemplo pode marcar para apagar tudo que estiver ha mais de 1 dia, colocando 1140, sendo (1 minuto * 60 minutos * 24 horas)

Após a configuração do plugin, é necessário colar o código script gerado no HTML adicional do moodle em ADMIN > APARÊNCIA > Código HTML adicional

Para um texto no chat mais rico, utilize marcadores html nas itents do Dialogflow:
Conversor de marcadores de texto em HTML
https://github.com/showdownjs/showdown

Developed and maintained by
===========================

copyright  2019 Marcelo Mendes, m2msolucoes.com.br

author     Marcelo Mendes do Amaral marcelo@m2msolcuoes.com.br

license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
