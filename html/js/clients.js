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
  getAllClients();
};

async function getAllClients() {
  try {
    const res = await axiosInstance.get('clients');
    const clientes = res.data;
    const tabela = document.getElementById('clientsTable');
    tabela.innerHTML = '';

    clientes.forEach(cliente => {
      tabela.innerHTML += `
        <tr>
          <td>${cliente.id}</td>
          <td>${cliente.name}</td>
          <td>${cliente.cpf}</td>
          <td>${cliente.address}</td>
          <td>
            <button class="btn btn-sm btn-warning" onclick="findClientsById(${cliente.id})">Update</button>
            <button class="btn btn-sm btn-danger" onclick="deleteClient(${cliente.id})">Delete</button>
          </td>
        </tr>
      `;
    });
  } catch (err) {
    alert('Erro ao carregar clientes');
    console.error(err.response?.data || err);
    if (err.response?.status === 401) {
      localStorage.removeItem('jwt_token');
      window.location.href = 'login.html';
    }
  }
}

document.getElementById('clienteForm').addEventListener('submit', async (e) => {
  e.preventDefault();
  const id = document.getElementById('clienteId').value;
  try {
    if (id) {
      await updateClient(id);
    } else {
      await createClient();
    }
    resetForm();
    getAllClients();
  } catch (err) {
    alert('Erro ao salvar cliente');
    console.error(err.response?.data || err);
  }
});

async function createClient() {
  const cliente = collectDataForm();
  await axiosInstance.post('clients', cliente);
}

async function updateClient(id) {
  const cliente = collectDataForm();
  await axiosInstance.put(`clients/${id}`, cliente);
}

async function findClientsById(id) {
  try {
    const res = await axiosInstance.get(`http://localhost/telecontrol/app/clients/find/${id}`);
    const cliente = res.data;
    document.getElementById('clienteId').value = cliente[0].id;
    document.getElementById('name').value = cliente[0].name;
    document.getElementById('cpf').value = cliente[0].cpf;
    document.getElementById('address').value = cliente[0].address;
  } catch (err) {
    alert('Error' + err);
    console.error(err.response?.data || err);
  }
}

async function deleteClient(id) {
  if (!confirm('Tem certeza que deseja excluir este cliente?')) return;
  try {
    await axiosInstance.delete(`clients/${id}`);
    getAllClients();
  } catch (err) {
    alert('Error' + err);
    console.error(err.response?.data || err);
  }
}

function collectDataForm() {
  return {
    name: document.getElementById('name').value.trim(),
    cpf: document.getElementById('cpf').value.trim(),
    address: document.getElementById('address').value.trim()
  };
}

function resetForm() {
  document.getElementById('clienteId').value = '';
  document.getElementById('clienteForm').reset();
}


