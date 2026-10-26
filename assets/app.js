const overlay = document.getElementById('overlay');
const form = document.getElementById('studentForm');
function openModal() {
  form.reset();
  document.getElementById('formAction').value = 'create';
  document.getElementById('studentId').value = '';
  document.getElementById('modalTitle').textContent = 'Add student';
  document.getElementById('saveButton').textContent = 'Save student';
  overlay.hidden = false;
  document.getElementById('studentNumber').focus();
}
function editStudent(student) {
  openModal();
  document.getElementById('formAction').value = 'update';
  document.getElementById('studentId').value = student.id;
  document.getElementById('studentNumber').value = student.student_number;
  document.getElementById('fullName').value = student.full_name;
  document.getElementById('email').value = student.email;
  document.getElementById('course').value = student.course;
  document.getElementById('yearLevel').value = student.year_level;
  document.getElementById('modalTitle').textContent = 'Edit student';
  document.getElementById('saveButton').textContent = 'Update student';
}
function closeModal() { overlay.hidden = true; }
overlay.addEventListener('click', e => { if (e.target === overlay) closeModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
