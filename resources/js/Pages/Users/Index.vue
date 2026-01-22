<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, useForm } from "@inertiajs/vue3";
import { ref, watch, computed } from "vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";
import { useToast } from "vue-toastification";

const props = defineProps({
  users: {
    type: Object,
    required: true,
  },
  roles: {
    type: Array,
    default: () => [],
  },
  filters: {
    type: Object,
  },
});

let search = ref(props.filters?.search || "");

const debouncedFetch = debounce((search) => {
  router.get(
    route("users.index"),
    { search },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
}, 300);

watch([search], (values) => {
  const [search] = values;
  debouncedFetch(search);
});

// Form for adding role to a specific user
const addRoleForm = ref({});
const showAddRoleForm = ref({});

const openAddRoleForm = (user) => {
  showAddRoleForm.value[user.id] = true;
  addRoleForm.value[user.id] = useForm({
    role_id: "",
  });
};

const closeAddRoleForm = (userId) => {
  showAddRoleForm.value[userId] = false;
  addRoleForm.value[userId] = null;
};

const assignRole = (user) => {
  if (!addRoleForm.value[user.id].role_id) {
    const toast = useToast();
    toast.warning("Please select a role", {
      timeout: 3000,
    });
    return;
  }

  addRoleForm.value[user.id].post(route("users.assign-role", user.id), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Role assigned successfully", {
        timeout: 3000,
      });
      closeAddRoleForm(user.id);
    },
    onError: (errors) => {
      const toast = useToast();
      const errorMessage =
        errors.role_id || errors.message || "Failed to assign role";
      toast.error(errorMessage, {
        timeout: 3000,
      });
    },
  });
};

const removeRole = (user, roleId) => {
  if (
    confirm(
      `Are you sure you want to remove this role from ${
        user.employee?.full_name || user.name || user.email
      }?`
    )
  ) {
    const form = useForm({
      role_id: roleId,
    });

    form.post(route("users.remove-role", user.id), {
      onSuccess: () => {
        const toast = useToast();
        toast.success("Role removed successfully", {
          timeout: 3000,
        });
      },
      onError: () => {
        const toast = useToast();
        toast.error("Failed to remove role", {
          timeout: 3000,
        });
      },
    });
  }
};

// Get available roles for a user (roles not already assigned)
const getAvailableRoles = (user) => {
  if (!user.roles || user.roles.length === 0) {
    return props.roles;
  }

  const assignedRoleIds = user.roles.map((role) => role.id);
  return props.roles.filter((role) => !assignedRoleIds.includes(role.id));
};
</script>

<template>
  <Head title="User Accounts" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">User Accounts</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
              All Users
            </li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="d-flex align-items-stretch">
        <div class="card w-100 rounded-4">
          <div class="card-body">
            <div class="d-flex align-items-start justify-content-between mb-4">
              <div class="">
                <h5 class="mb-0">User Accounts Management</h5>
                <p class="text-muted mb-0">
                  Manage user accounts and assign multiple roles
                </p>
              </div>
            </div>

            <!-- Search inputs -->
            <form class="row g-3 mb-5">
              <div class="col-md-4">
                <label for="search" class="form-label">Search</label>
                <input
                  type="text"
                  class="form-control"
                  id="search"
                  placeholder="Name, Email, or Employee ID"
                  v-model="search"
                  autocomplete="off"
                />
              </div>
            </form>
            <!-- End of search inputs -->

            <!-- Table -->
            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Employee ID</th>
                    <th>Roles</th>
                    <th>Status</th>
                    <th>Registered At</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <p class="mb-0">
                          {{ user.employee?.full_name || user.name || "-" }}
                        </p>
                      </div>
                    </td>
                    <td>{{ user.email }}</td>
                    <td>
                      {{ user.employee?.employee_id || "-" }}
                    </td>
                    <td>
                      <div class="d-flex flex-wrap gap-1 mb-2">
                        <span
                          v-for="role in user.roles"
                          :key="role.id"
                          class="badge bg-primary me-1"
                        >
                          {{ role.name }}
                          <button
                            class="btn-close btn-close-white ms-1"
                            style="font-size: 0.7em"
                            @click="removeRole(user, role.id)"
                            title="Remove role"
                          ></button>
                        </span>
                        <span
                          v-if="!user.roles || user.roles.length === 0"
                          class="badge bg-secondary"
                        >
                          No Role
                        </span>
                      </div>
                      <!-- Add Role Form -->
                      <div v-if="showAddRoleForm[user.id]" class="mt-2">
                        <div class="d-flex gap-2 align-items-center">
                          <select
                            class="form-select form-select-sm"
                            style="min-width: 150px"
                            v-model="addRoleForm[user.id].role_id"
                          >
                            <option value="">Select Role</option>
                            <option
                              v-for="role in getAvailableRoles(user)"
                              :key="role.id"
                              :value="role.id"
                            >
                              {{ role.name }}
                            </option>
                          </select>
                          <button
                            class="btn btn-sm btn-success"
                            @click="assignRole(user)"
                            :disabled="addRoleForm[user.id].processing"
                          >
                            <i class="bi bi-check-lg"></i> Add
                          </button>
                          <button
                            class="btn btn-sm btn-secondary"
                            @click="closeAddRoleForm(user.id)"
                            :disabled="addRoleForm[user.id].processing"
                          >
                            <i class="bi bi-x-lg"></i> Cancel
                          </button>
                        </div>
                        <div
                          v-if="addRoleForm[user.id]?.errors?.role_id"
                          class="text-danger small mt-1"
                        >
                          {{ addRoleForm[user.id].errors.role_id }}
                        </div>
                      </div>
                      <button
                        v-else
                        class="btn btn-sm btn-outline-primary"
                        @click="openAddRoleForm(user)"
                        :disabled="getAvailableRoles(user).length === 0"
                        title="Add Role"
                      >
                        <i class="bi bi-plus-lg"></i> Add Role
                      </button>
                    </td>
                    <td>
                      <span v-if="user.is_active" class="badge bg-success">
                        Active
                      </span>
                      <span v-else class="badge bg-danger">Inactive</span>
                    </td>
                    <td>
                      {{ new Date(user.created_at).toLocaleDateString() }}
                    </td>
                  </tr>
                  <tr v-if="users.data.length === 0">
                    <td colspan="7" class="text-center text-muted py-4">
                      No users found
                    </td>
                  </tr>
                </tbody>
              </table>
              <Pagination :links="users.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
