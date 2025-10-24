<?php
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de planning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
</head>
<body class="bg-light">
<div class="container py-4">
    <h1 class="text-center mb-4">🗓️ Gestion de planning</h1>
    <div id='calendar'></div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="eventModalLabel">Créer / Modifier un planning</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="eventForm">
          <input type="hidden" id="eventId">
          <div class="mb-3">
            <label for="employeeSelect" class="form-label">Employé</label>
            <select id="employeeSelect" class="form-select" required></select>
          </div>
          <div class="mb-3">
            <label for="startDate" class="form-label">Date début</label>
            <input type="datetime-local" id="startDate" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="endDate" class="form-label">Date fin</label>
            <input type="datetime-local" id="endDate" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="comment" class="form-label">Commentaire</label>
            <textarea id="comment" class="form-control" rows="2"></textarea>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" id="deleteEvent" class="btn btn-danger d-none">Supprimer</button>
        <button type="button" id="saveEvent" class="btn btn-primary">Enregistrer</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function() {
  const calendarEl = document.getElementById('calendar');
  const modal = new bootstrap.Modal(document.getElementById('eventModal'));
  const eventId = document.getElementById('eventId');
  const employeeSelect = document.getElementById('employeeSelect');
  const startDate = document.getElementById('startDate');
  const endDate = document.getElementById('endDate');
  const comment = document.getElementById('comment');
  const saveEvent = document.getElementById('saveEvent');
  const deleteEvent = document.getElementById('deleteEvent');

  // Charger employés
  const res = await fetch('api/employees.php');
  const employees = await res.json();
  employeeSelect.innerHTML = employees.map(e => `<option value="\${e.id}">\${e.name}</option>`).join('');

  // Charger calendrier
  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    locale: 'fr',
    editable: true,
    selectable: true,
    events: 'api/events.php',
    select: (info) => {
      eventId.value = '';
      employeeSelect.selectedIndex = 0;
      startDate.value = info.startStr.slice(0,16);
      endDate.value = info.endStr.slice(0,16);
      comment.value = '';
      deleteEvent.classList.add('d-none');
      modal.show();
    },
    eventClick: (info) => {
      const ev = info.event;
      eventId.value = ev.id;
      employeeSelect.value = ev.extendedProps.employee_id;
      startDate.value = ev.startStr.slice(0,16);
      endDate.value = ev.endStr.slice(0,16);
      comment.value = ev.extendedProps.comment || '';
      deleteEvent.classList.remove('d-none');
      modal.show();
    },
  });

  saveEvent.onclick = async () => {
    if (!employeeSelect.value || !startDate.value || !endDate.value) return alert('Veuillez remplir tous les champs.');
    const data = {
      id: eventId.value,
      employee_id: employeeSelect.value,
      start: startDate.value,
      end: endDate.value,
      comment: comment.value
    };
    const method = data.id ? 'PUT' : 'POST';
    await fetch('api/events.php', {
      method,
      headers: {'Content-Type': 'application/json'},
      body: JSON.stringify(data)
    });
    modal.hide();
    calendar.refetchEvents();
  };

  deleteEvent.onclick = async () => {
    if (confirm('Supprimer ce planning ?')) {
      await fetch('api/events.php', {
        method: 'DELETE',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ id: eventId.value })
      });
      modal.hide();
      calendar.refetchEvents();
    }
  };

  calendar.render();
});
</script>
</body>
</html>
