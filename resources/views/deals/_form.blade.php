<!-- Nombre del Deal -->
<div class="mb-6">
    <x-input-label for="name" value="Nombre del Deal:" />
    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $deal->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mb-4">
    <label for="notes_contact" class="block text-gray-700 dark:text-gray-300 text-sm font-bold mb-2">
        Notas de Contacto Inicial:
    </label>
    <textarea name="notes_contact" id="notes_contact" rows="4" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 dark:text-gray-300 dark:bg-gray-900 leading-tight focus:outline-none focus:shadow-outline">{{ old('notes_contact', $deal->notes_contact ?? '') }}</textarea>
    @error('notes_contact') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
</div>

<!-- Valor -->
<div class="mb-6">
    <x-input-label for="value" value="Valor ($):" />
    <x-text-input id="value" name="value" type="number" step="0.01" class="mt-1 block w-full" :value="old('value', $deal->value ?? '')" placeholder="Ej: 1500.00" />
    <x-input-error :messages="$errors->get('value')" class="mt-2" />
</div>

<!-- Fecha de Cierre Prevista -->
<div class="mb-6">
    <x-input-label for="expected_close_date" value="Fecha de Cierre Prevista:" />
    <x-text-input id="expected_close_date" name="expected_close_date" type="date" class="mt-1 block w-full" :value="old('expected_close_date', $deal->expected_close_date ? \Carbon\Carbon::parse($deal->expected_close_date)->format('Y-m-d') : '')" />
    <x-input-error :messages="$errors->get('expected_close_date')" class="mt-2" />
</div>

<!-- Cliente Asociado -->
<div class="mb-6">
    <x-input-label for="client_id" value="Cliente:" />
    <select name="client_id" id="client_id" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none" required>
        <option value="" disabled selected>Selecciona un cliente...</option>
        @foreach ($clients as $client)
            <option value="{{ $client->id }}" @selected(old('client_id', $deal->client_id ?? '') == $client->id)>
                {{ $client->name }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('client_id')" class="mt-2" />
</div>

<!-- Establecimiento -->
<div id="establishment-wrapper" class="mb-6" style="display: none;">
    <x-input-label for="establishment_id" value="Establecimiento:" />
    <select name="establishment_id" id="establishment_id" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none">
        <option value="" disabled selected>Selecciona un establecimiento...</option>
    </select>
    <x-input-error :messages="$errors->get('establishment_id')" class="mt-2" />
</div>

<!-- Contacto Principal -->
<div id="primary-contact-wrapper" class="mb-6" style="display: none;">
    <x-input-label for="primary_contact_id" value="Contacto Principal:" />
    <select name="primary_contact_id" id="primary_contact_id" class="mt-1 block w-full bg-gray-900/60 border border-white/10 rounded-lg text-light-text transition-all duration-300 focus:border-aurora-cyan focus:ring-2 focus:ring-aurora-cyan/40 focus:outline-none">
        <option value="" disabled selected>Selecciona un contacto principal...</option>
    </select>
    <x-input-error :messages="$errors->get('primary_contact_id')" class="mt-2" />
</div>

<!-- Contactos Adicionales -->
<div id="additional-contacts-wrapper" class="mb-6" style="display: none;">
    <x-input-label for="contacts" value="Contactos Adicionales:" />
    <div id="additional-contacts-list" class="mt-2 space-y-2">
        {{-- Checkboxes will be inserted here by JavaScript --}}
    </div>
    <x-input-error :messages="$errors->get('contacts')" class="mt-2" />
</div>


<!-- Botones de Acción -->
<div class="flex items-center justify-end mt-8 space-x-4">
    @php
        $cancelUrl = route('deals.index');
        if (request('from_client_show') && isset($deal->client_id)) {
            $cancelUrl = route('clients.show', $deal->client_id);
        }
    @endphp
    <a href="{{ $cancelUrl }}">
        <x-secondary-button type="button">Cancelar</x-secondary-button>
    </a>
    <x-primary-button>{{ $btnText }}</x-primary-button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const clientSelect = document.getElementById('client_id');
        const establishmentWrapper = document.getElementById('establishment-wrapper');
        const establishmentSelect = document.getElementById('establishment_id');
        const primaryContactWrapper = document.getElementById('primary-contact-wrapper');
const primaryContactSelect = document.getElementById('primary_contact_id');
        const additionalContactsWrapper = document.getElementById('additional-contacts-wrapper');
        const additionalContactsList = document.getElementById('additional-contacts-list');

        const selectedPrimaryContact = "{{ old('primary_contact_id', $deal->primary_contact_id ?? '') }}";
        const selectedAdditionalContacts = @json(old('contacts', $deal->contacts ? $deal->contacts->pluck('id') : []));

        function updateDropdowns(clientId) {
            if (!clientId) {
                establishmentWrapper.style.display = 'none';
                primaryContactWrapper.style.display = 'none';
                additionalContactsWrapper.style.display = 'none';
                return;
            }

            fetch(`/clients/${clientId}/data`)
                .then(response => response.json())
                .then(data => {
                    // Clear previous options
                    establishmentSelect.innerHTML = '<option value="" disabled selected>Selecciona un establecimiento...</option>';
                    primaryContactSelect.innerHTML = '<option value="" disabled selected>Selecciona un contacto principal...</option>';
                    additionalContactsList.innerHTML = '';

                    // Populate establishments
                    if (data.establishments && data.establishments.length > 0) {
                        data.establishments.forEach(establishment => {
                            const option = new Option(establishment.name, establishment.id);
                            establishmentSelect.add(option);
                        });
                        establishmentWrapper.style.display = 'block';
                    } else {
                        establishmentWrapper.style.display = 'none';
                    }

                    // Populate contacts
                    if (data.contacts && data.contacts.length > 0) {
                        data.contacts.forEach(contact => {
                            // Add to primary contact dropdown
                            const primaryOption = new Option(contact.name, contact.id);
                            if (contact.id == selectedPrimaryContact) {
                                primaryOption.selected = true;
                            }
                            primaryContactSelect.add(primaryOption);

                            // Add to additional contacts list
                            const checkboxDiv = document.createElement('div');
                            checkboxDiv.classList.add('flex', 'items-center');
                            checkboxDiv.id = `contact-checkbox-${contact.id}`;

                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.name = 'contacts[]';
                            checkbox.value = contact.id;
                            checkbox.id = `contact-${contact.id}`;
                            checkbox.classList.add('rounded', 'dark:bg-gray-900', 'border-gray-300', 'dark:border-gray-700', 'text-indigo-600', 'shadow-sm', 'focus:ring-indigo-500', 'dark:focus:ring-indigo-600', 'dark:focus:ring-offset-gray-800');

                            if (selectedAdditionalContacts.includes(contact.id)) {
                                checkbox.checked = true;
                            }

                            const label = document.createElement('label');
                            label.htmlFor = `contact-${contact.id}`;
                            label.textContent = contact.name;
                            label.classList.add('ml-2', 'text-sm', 'text-gray-600', 'dark:text-gray-400');

                            checkboxDiv.appendChild(checkbox);
                            checkboxDiv.appendChild(label);

                            additionalContactsList.appendChild(checkboxDiv);
                        });
                        primaryContactWrapper.style.display = 'block';
                        additionalContactsWrapper.style.display = 'block';
                        filterAdditionalContacts(); // Call filter after populating
                    } else {
                        primaryContactWrapper.style.display = 'none';
                        additionalContactsWrapper.style.display = 'none';
                    }
                });
        }

        function filterAdditionalContacts() {
            const selectedPrimaryContactId = primaryContactSelect.value;
            const checkboxes = additionalContactsList.querySelectorAll('input[type="checkbox"]');

            checkboxes.forEach(checkbox => {
                const contactId = checkbox.value;
                const checkboxDiv = document.getElementById(`contact-checkbox-${contactId}`);

                if (contactId === selectedPrimaryContactId) {
                    checkboxDiv.style.display = 'none';
                    checkbox.checked = false; // Uncheck if it was selected
                } else {
                    checkboxDiv.style.display = 'flex';
                }
            });
        }

        // Initial call on page load if a client is already selected
        if (clientSelect.value) {
            updateDropdowns(clientSelect.value);
        }

        // Event listener for client selection change
        clientSelect.addEventListener('change', function () {
            updateDropdowns(this.value);
        });

        // Event listener for primary contact selection change
        primaryContactSelect.addEventListener('change', function () {
            filterAdditionalContacts();
        });
    });
</script>
@endpush()