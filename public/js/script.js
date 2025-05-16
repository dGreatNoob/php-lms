document.addEventListener('DOMContentLoaded', () => {
  const courseSelect = document.getElementById('course_id');
  const modal = document.getElementById('courseModal');
  const addCourseForm = document.getElementById('addCourseForm');
  const modalMsg = document.getElementById('modalMsg');
  const closeModalBtn = document.getElementById('closeModal');

  // Open modal when "Add new course" selected
  courseSelect.addEventListener('change', () => {
    if (courseSelect.value === 'add_new') {
      modal.style.display = 'flex';
      modalMsg.style.display = 'none';
      addCourseForm.reset();
    }
  });

  // Close modal
  closeModalBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    courseSelect.value = ""; // reset selection
  });

  // Submit new course via AJAX
  addCourseForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    modalMsg.style.display = 'none';

    const formData = new FormData(addCourseForm);
    const title = formData.get('title').trim();

    if (!title) {
      modalMsg.textContent = 'Course title cannot be empty.';
      modalMsg.style.display = 'block';
      return;
    }

    try {
      const res = await fetch('create_course_ajax.php', {
        method: 'POST',
        body: formData,
      });
      const data = await res.json();

      if (data.success) {
        // Add new course to dropdown
        const newOption = document.createElement('option');
        newOption.value = data.course_id;
        newOption.textContent = title;
        courseSelect.insertBefore(newOption, courseSelect.querySelector('option[value="add_new"]'));
        courseSelect.value = data.course_id;

        modal.style.display = 'none';
      } else {
        modalMsg.textContent = data.error || 'Failed to add course.';
        modalMsg.style.display = 'block';
      }
    } catch (err) {
      modalMsg.textContent = 'Error connecting to server.';
      modalMsg.style.display = 'block';
    }
  });
});
