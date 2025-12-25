import { api, auth, toast } from "/scripts/util.js";

async function init(){
  document.getElementById('regBtn').addEventListener('click', registerUser);
}

async function registerUser(){
  const firstName = document.getElementById('firstName').value.trim();
  const lastName = document.getElementById('lastName').value.trim();
  const username = document.getElementById('username').value.trim();
  const password = document.getElementById('password').value.trim();
  const confirmPassword = document.getElementById('confirmPassword').value.trim();

  if(!firstName || !lastName || !username || !password || !confirmPassword){ return toast.show('All fields are required'); }
  try{
    await api.post('/api/users', { firstName, lastName, username, password, confirmPassword });
    toast.show('User created');
  }catch(e){ toast.show(e.message); }
}

init();
