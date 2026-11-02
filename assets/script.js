const $ = (s, r = document) => r.querySelector(s);

document.querySelectorAll('[data-open]').forEach(b =>
  b.addEventListener('click', () => $('#' + b.dataset.open).showModal()));

document.querySelectorAll('dialog').forEach(d => {
  d.addEventListener('click', e => { if (e.target === d) d.close(); });
  d.querySelectorAll('[data-close]').forEach(b => b.addEventListener('click', () => d.close()));
});

const fileInput = $('input[type=file]');
fileInput.addEventListener('change', () => {
  const n = fileInput.files.length;
  $('#fileInfo').textContent = n ? n + ' foto dipilih' : 'Pilih satu atau banyak foto';
});

function openPhoto(el) {
  $('#vImg').src = el.dataset.src;
  $('#vCap').textContent = el.dataset.cap;
  const by = el.dataset.by ? 'oleh ' + el.dataset.by + ' · ' : '';
  $('#vBy').textContent = by + el.dataset.date;
  $('#vId').value = el.dataset.id;
  $('#dlgView').showModal();
}
document.querySelectorAll('.scrap').forEach(el => {
  el.addEventListener('click', () => openPhoto(el));
  el.addEventListener('keydown', e => {
    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openPhoto(el); }
  });
});

function askPass() {
  const p = prompt('Masukkan password untuk melepas foto ini:');
  if (!p) return false;
  $('#vPass').value = p;
  return true;
}
window.askPass = askPass;
