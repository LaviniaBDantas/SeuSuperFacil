// Exemplo de JavaScript para desabilitar o envio de formulários se houver campos inválidos
(() => {
  'use strict'

  // Busca todos os formulários que queremos aplicar o estilo de validação do Bootstrap
  const forms = document.querySelectorAll('.needs-validation')

  // Loop sobre eles e previne o envio se forem inválidos
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', event => {
      if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
      }

      form.classList.add('was-validated')
    }, false)
  })
})()

// Função para atualizar o contador de itens no ícone do carrinho
function atualizarContadorCarrinho() {
    let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    let totalItens = carrinho.reduce((total, item) => total + item.quantidade, 0);
    let cartCountElement = document.getElementById('cart-count');

    // Verifica se o elemento do contador existe
    if (!cartCountElement) {
        console.error("Elemento 'cart-count' não encontrado na página.");
        return;
    }

    // Atualiza o número de itens no badge
    if (totalItens > 0) {
        cartCountElement.textContent = totalItens;
        cartCountElement.style.display = 'block'; // Mostra o badge
    } else {
        cartCountElement.style.display = 'none'; // Esconde o badge se não houver itens
    }

    // Depuração para ver o estado atual do contador
    console.log("Total de itens no carrinho:", totalItens);
}

// Função para adicionar um item ao carrinho
function adicionarAoCarrinho(produtoId, descricao, preco) {
    let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Verifica se o produto já está no carrinho
    let produtoExistente = carrinho.find(item => item.id === produtoId);

    if (produtoExistente) {
        // Se o produto já estiver no carrinho, aumenta a quantidade
        produtoExistente.quantidade += 1;
    } else {
        // Se o produto for novo, adiciona ao carrinho
        carrinho.push({
            id: produtoId,
            descricao: descricao,
            preco: preco,
            quantidade: 1
        });
    }

    // Atualiza o localStorage com o novo carrinho
    localStorage.setItem('carrinho', JSON.stringify(carrinho));

    // Verificação de depuração
    console.log("Carrinho atualizado:", carrinho);

    // Atualiza o contador de itens no ícone do carrinho
    atualizarContadorCarrinho();

    // Notifica o usuário que o item foi adicionado
    alert('Produto adicionado ao carrinho!');
}

// Função para remover um item do carrinho
function removerItem(produtoId) {
    let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

    // Filtra o carrinho para remover o item com o produtoId especificado
    carrinho = carrinho.filter(item => item.id !== produtoId);

    // Atualiza o localStorage com o novo carrinho
    localStorage.setItem('carrinho', JSON.stringify(carrinho));

    // Recarrega o contador e o carrinho na interface
    atualizarContadorCarrinho();
    carregarCarrinho(); // Se houver uma função para recarregar o carrinho na página

    console.log(`Item com ID ${produtoId} removido. Novo carrinho:`, carrinho);
}

// Função para carregar o carrinho (usado para exibir os itens em carrinho.html)
function carregarCarrinho() {
    let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
    let listaCarrinho = document.getElementById('lista-carrinho');
    let totalItens = document.getElementById('total-itens');
    let totalPreco = document.getElementById('total-preco');

    // Limpa a lista antes de preenchê-la
    listaCarrinho.innerHTML = '';
    let total = 0;
    let totalQuantidade = 0;

    // Verifica se o carrinho está vazio
    if (carrinho.length === 0) {
        listaCarrinho.innerHTML = '<li class="list-group-item">Seu carrinho está vazio.</li>';
        totalItens.textContent = '0 itens';
        totalPreco.textContent = 'R$ 0,00';
        return;
    }

// Itera sobre os itens do carrinho e os insere na lista
carrinho.forEach(item => {
  let itemTotal = item.preco * item.quantidade;
  total += itemTotal;
  totalQuantidade += item.quantidade;

  // Cria a estrutura do item no carrinho
  let li = document.createElement('li');
  li.className = "list-group-item d-flex justify-content-between lh-sm";
  li.innerHTML = `
      <div>
          <h6 class="my-0">${item.descricao}</h6>
          <small class="text-muted">Preço unitário: R$ ${item.preco.toFixed(2).replace('.', ',')}</small>
      </div>
      <span class="text-muted">R$ ${itemTotal.toFixed(2).replace('.', ',')}</span>
      <input type="number" class="form-control quantidade-input" value="${item.quantidade}" min="1" style="width: 60px; margin-left: 15px;" data-id="${item.id}">
      <button class="btn btn-danger btn-sm" onclick="removerItem(${item.id})">Remover</button>
  `;

  listaCarrinho.appendChild(li);
});

// Atualiza o total de itens e o preço total no carrinho
totalItens.textContent = `${totalQuantidade} item${totalQuantidade > 1 ? 's' : ''}`;
totalPreco.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

// Função para atualizar a quantidade de itens no carrinho
function atualizarQuantidade(produtoId, novaQuantidade) {
let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];

// Encontra o item no carrinho e atualiza a quantidade
carrinho.forEach(item => {
  if (item.id === produtoId) {
      item.quantidade = novaQuantidade;
      console.log(`Quantidade do produto ${produtoId} atualizada para ${novaQuantidade}`);
  }
});

// Atualiza o localStorage com a nova quantidade
localStorage.setItem('carrinho', JSON.stringify(carrinho));

// Recarrega o carrinho e o contador
carregarCarrinho();
atualizarContadorCarrinho();
}

// Função para lidar com a mudança de quantidade via input (campo numérico)
document.addEventListener('input', function(event) {
if (event.target.classList.contains('quantidade-input')) {
  let produtoId = parseInt(event.target.getAttribute('data-id'));
  let novaQuantidade = parseInt(event.target.value);

  // Atualiza a quantidade se for um número válido e maior que zero
  if (novaQuantidade > 0) {
      atualizarQuantidade(produtoId, novaQuantidade);
  } else {
      // Se a quantidade for inválida (menor que 1), retorna ao valor anterior
      event.target.value = 1;
      atualizarQuantidade(produtoId, 1);
  }
}
});

// Carregar o carrinho e o contador quando a página for carregada
document.addEventListener('DOMContentLoaded', function() {
console.log("Página carregada, carregando carrinho e contador.");
carregarCarrinho();  // Se estiver na página do carrinho
atualizarContadorCarrinho();  // Em ambas as páginas para atualizar o badge do carrinho
});