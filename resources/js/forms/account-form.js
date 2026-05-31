document.addEventListener('DOMContentLoaded', () => {
    const roleSelect = document.getElementById('register-role');
    const licenseGroup = document.getElementById('license-group');
    const crewRoleGroup = document.getElementById('crew-role-group');
    const passportGroup = document.getElementById('passport-group');

    if (!roleSelect || !licenseGroup || !passportGroup || !crewRoleGroup) {
        return;
    }

    const updateGroups = () => {
        const role = roleSelect.value;

        if (role === 'tripulacion') {
            licenseGroup.classList.remove('hidden');
            crewRoleGroup.classList.remove('hidden');
            passportGroup.classList.add('hidden');
            return;
        }

        if (role === 'pasajero') {
            licenseGroup.classList.add('hidden');
            crewRoleGroup.classList.add('hidden');
            passportGroup.classList.remove('hidden');
            return;
        }

        licenseGroup.classList.add('hidden');
        crewRoleGroup.classList.add('hidden');
        passportGroup.classList.add('hidden');
    };

    updateGroups();
    roleSelect.addEventListener('change', updateGroups);
});
