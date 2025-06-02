const baseURL = 'http://localhost/telecontrol/app/';
const token = localStorage.getItem('jwt_token');

const axiosInstance = axios.create({
  baseURL,
  headers: {
    'Authorization': `Bearer ${token}`,
    'Content-Type': 'application/json'
  }
});

window.onload = () => {
  if (!token) {
    window.location.href = 'login.html';
    return;
  }
  getAllProducts();
};

async function getAllProducts() {
  try {
    const res = await axiosInstance.get('products');
    const produtos = res.data;
    const tabela = document.getElementById('tableProducts');
    tabela.innerHTML = '';

    produtos.forEach(produto => {
      tabela.innerHTML += `
        <tr>
          <td>${produto.id}</td>
          <td>${produto.description}</td>
          <td>${produto.status}</td>
          <td>${produto.warranty_time}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="findProductsById(${produto.id})">Update</button>
            <button class="btn btn-sm btn-danger" onclick="deleteProduct(${produto.id})">Delete</button>
          </td>
        </tr>
      `;
    });
  } catch (err) {
    alert('Erro ao carregar produtos');
    console.error(err.response?.data || err);
    if (err.response?.status === 401) {
      localStorage.removeItem('jwt_token');
      window.location.href = 'login.html';
    }
  }
}

document.getElementById('produtoForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = document.getElementById('productId').value;
  try {
    if (id) {
      await updateProduct(id);
    } else {
      await createProduct();
    }
    resetForm();
    getAllProducts();
  } catch (err) {
    alert('Error' + err);
    console.error(err.response?.data || err);
  }
});

async function createProduct() {
  const produto = collectDataForm();
  await axiosInstance.post('products', produto);
}

async function updateProduct(id) {
  const produto = collectDataForm();
  await axiosInstance.put(`products/${id}`, produto);
}

async function findProductsById(id) {
  try {
    const res = await axiosInstance.get(`http://localhost/telecontrol/app/products/find/${id}`);
    const produto = res.data;
    document.getElementById('productId').value = produto[0].id;
    document.getElementById('description').value = produto[0].description;
    document.getElementById('status').value = produto[0].status;
    document.getElementById('warranty_time').value = produto[0].warranty_time;
  } catch (err) {
    alert('Erro ao buscar produto');
    console.error(err.response?.data || err);
  }
}

async function deleteProduct(id) {
  if (!confirm('Tem certeza que deseja excluir este produto?')) return;
  try {
    await axiosInstance.delete(`products/${id}`);
    getAllProducts();
  } catch (err) {
    alert('Error' + err);
    console.error(err.response?.data || err);
  }
}

function collectDataForm() {
  return {
    description: document.getElementById('description').value.trim(),
    status: document.getElementById('status').value.trim(),
    warranty_time: parseInt(document.getElementById('warranty_time').value)
  };
}

function resetForm() {
  document.getElementById('productId').value = '';
  document.getElementById('produtoForm').reset();
}


