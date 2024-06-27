<!-- resources/views/admin/users/edit.blade.php -->
<!-- modal edit user -->
<div id="hs-focus-management-modaleditadmin"
    class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none">
    <div
        class="hs-overlay-open:mt-5 hs-overlay-open:opacity-100 hs-overlay-open:duration-500 mt-0 opacity-0 ease-out transition-all sm:max-w-lg sm:w-full m-3 sm:mx-auto">
        <div
            class="flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto dark:bg-neutral-800 dark:border-neutral-700 dark:shadow-neutral-700/70">
            <div class="flex justify-between items-center py-3 px-4 border-b dark:border-neutral-700"
                style="box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <h3 id="edit-nama" class="font-bold text-gray-800 dark:text-white">
                    EDIT USER: <span id="edit-user-name"></span>
                </h3>
                <!-- button close (x)  -->
                <button type="button"
                    class="flex justify-center items-center size-7 text-sm font-semibold rounded-full border border-transparent text-gray-800 hover:bg-gray-100 disabled:opacity-50 disabled:pointer-events-none dark:text-white dark:hover:bg-neutral-700"
                    data-hs-overlay="#hs-focus-management-modaleditadmin">
                    <span class="sr-only">Close</span>
                    <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <!-- form edit user -->
            <div class="p-2 overflow-y-auto">
                <form method="POST" action="/users-update">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" id="edit-user" value="">
                    <!-- dropdown opsi role user -->
                    <div class="p-4 overflow-y-auto">
                        <label for="role"
                            class="block text-sm font-medium mb-2 text-left dark:text-white">Role</label>
                        <select name="role" id="role"
                            class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:placeholder-neutral-500 dark:text-neutral-400">
                            <option value="admin">Admin</option>
                            <option value="organik">Organik</option>
                        </select>
                    </div>
                    <!-- tombol batal dan simpan edituser -->
                    <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t dark:border-neutral-700">
                        <button type="button"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-red-600 text-white shadow-sm hover:bg-red-700 disabled:opacity-50 disabled:pointer-events-none"
                            data-hs-overlay="#hs-focus-management-modaleditadmin">Batal</button>
                        <button type="submit"
                            class="py-2 px-3 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:pointer-events-none">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    //menampilkan data edit 
    function openEditModalUser(button) {
        const id = button.getAttribute('data-id');
        console.log(`User ID: ${id}`);
        fetch(`/admin/users-edit/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {

                const editUserName = document.getElementById('edit-user-name');
                const form = document.querySelector('form');
                const userId = document.querySelector('#edit-user');
                const roleSelect = document.getElementById('role');

                editUserName.textContent = data.name;
                userId.value = data.id;
                form.action = `/admin/users-update/${data.id}`;

                // Reset role select options
                roleSelect.value = '';

                // Update role select options based on fetched data
                roleSelect.value = data.role;

                console.log(`Role selected: ${roleSelect.value}`);
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    }
</script>
