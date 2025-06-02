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
  getAllOrders();
};

async function getAllOrders() {
  try {
    const res = await axiosInstance.get('orders');
    const orders = res.data;
    const tabela = document.getElementById('tableServiceOrder');
    tabela.innerHTML = '';

    orders.forEach(order => {
      tabela.innerHTML += `
        <tr>
          <td>${order.id}</td>
          <td>${order.consumer_name}</td>
          <td>${order.consumer_cpf}</td>
          <td>${order.open_date}</td>
          <td>${order.product_id}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="findOrdersById(${order.id})">Update</button>
            <button class="btn btn-sm btn-danger" onclick="deleteOrder(${order.id})">Delete</button>
          </td>
        </tr>
      `;
    });
  } catch (err) {
    alert('Erro ao carregar ordens de serviço');
    console.error(err.response?.data || err);
    if (err.response?.status === 401) {
      localStorage.removeItem('jwt_token');
      window.location.href = 'login.html';
    }
  }
}

document.getElementById('orderForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = document.getElementById('orderId').value;
  try {
    if (id) {
      await updateOrder(id);
    } else {
      await createOrder();
    }
    resetForm();
    getAllOrders();
  } catch (err) {
    alert('Error' + err);
    console.error(err.response?.data || err);
  }
});

async function createOrder() {
  const cliente = collectDataForm();
  await axiosInstance.post('orders', cliente);
}

async function updateOrder(id) {
  const cliente = collectDataForm();
  await axiosInstance.put(`orders/${id}`, cliente);
}

async function findOrdersById(id) {
  try {
    const res = await axiosInstance.get(`http://localhost/telecontrol/app/orders/find/${id}`);
    const order = res.data;
    document.getElementById('orderId').value = order[0].id;
    document.getElementById('name').value = order[0].consumer_name;
    document.getElementById('cpf').value = order[0].consumer_cpf;
    document.getElementById('openDate').value = order[0].open_date;
    document.getElementById('productId').value = order[0].product_id;
  } catch (err) {
    alert('Error' + err);
    console.error(err.response?.data || err);
  }
}

async function deleteOrder(id) {
  if (!confirm('Tem certeza que deseja excluir esta ordem de serviço?')) return;
  try {
    await axiosInstance.delete(`orders/${id}`);
    getAllOrders();
  } catch (err) {
    alert('Error' + err);
    console.error(err.response?.data || err);
  }
}

function collectDataForm() {
  return {
    consumer_name: document.getElementById('name').value.trim(),
    consumer_cpf: document.getElementById('cpf').value.trim(),
    open_date: document.getElementById('openDate').value.trim(),
    product_id : document.getElementById('productId').value.trim(),
  };
}

function resetForm() {
  document.getElementById('orderId').value = '';
  document.getElementById('orderForm').reset();
}


