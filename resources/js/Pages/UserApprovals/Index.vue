<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";
import { useToast } from "vue-toastification";

const props = defineProps({
  users: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
  },
});

let search = ref(props.filters.search);

const debouncedFetch = debounce((search) => {
  router.get(
    route("user-approvals.index"),
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

const approveUser = (user) => {
  router.post(
    route("user-approvals.approve", user),
    {},
    {
      onSuccess: () => {
        const toast = useToast();
        toast.success("User approved successfully", {
          timeout: 3000,
        });
      },
    }
  );
};

const rejectUser = (user) => {
  if (
    confirm(
      `Are you sure you want to reject and delete ${
        user.full_name || user.email
      }?`
    )
  ) {
    router.delete(route("user-approvals.reject", user), {
      onSuccess: () => {
        const toast = useToast();
        toast.success("User rejected and deleted", {
          timeout: 3000,
        });
      },
    });
  }
};

const selectedUsers = ref([]);

const toggleUserSelection = (userId) => {
  const index = selectedUsers.value.indexOf(userId);
  if (index > -1) {
    selectedUsers.value.splice(index, 1);
  } else {
    selectedUsers.value.push(userId);
  }
};

const bulkApproveForm = useForm({
  user_ids: [],
});

const bulkApprove = () => {
  if (selectedUsers.value.length === 0) {
    const toast = useToast();
    toast.warning("Please select at least one user", {
      timeout: 3000,
    });
    return;
  }

  bulkApproveForm.user_ids = selectedUsers.value;
  bulkApproveForm.post(route("user-approvals.bulk-approve"), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Selected users approved successfully", {
        timeout: 3000,
      });
      selectedUsers.value = [];
    },
  });
};
</script>

<template>
  <Head title="User Approvals" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">User Approvals</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Pending</li>
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
                <h5 class="mb-0">Pending User Approvals</h5>
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
                  placeholder="Name or Email"
                  v-model="search"
                  autocomplete="off"
                />
              </div>
            </form>
            <!-- End of search inputs -->

            <!-- Bulk actions -->
            <div
              v-if="selectedUsers.length > 0"
              class="d-flex align-items-start justify-content-between mb-4"
            >
              <div class="text-muted">
                {{ selectedUsers.length }} user(s) selected
              </div>
              <button class="btn btn-success px-4" @click="bulkApprove">
                <i class="bi bi-check-all me-2"></i>Approve Selected
              </button>
            </div>
            <!-- End of bulk actions -->

            <!-- Table -->
            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="table-dark">
                  <tr>
                    <th style="width: 50px">
                      <input
                        type="checkbox"
                        @change="
                          selectedUsers = $event.target.checked
                            ? users.data.map((u) => u.id)
                            : []
                        "
                      />
                    </th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Employee ID</th>
                    <th>Registered At</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="user in users.data" :key="user.id">
                    <td>
                      <input
                        type="checkbox"
                        :checked="selectedUsers.includes(user.id)"
                        @change="toggleUserSelection(user.id)"
                      />
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <p class="mb-0">
                          {{ user.full_name || user.name || "-" }}
                        </p>
                      </div>
                    </td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.position || "-" }}</td>
                    <td>
                      {{ user.employee?.employee_id || "-" }}
                    </td>
                    <td>
                      {{ new Date(user.created_at).toLocaleDateString() }}
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-1">
                        <button
                          class="btn btn-sm btn-success"
                          @click="approveUser(user)"
                        >
                          <i class="bi bi-check-lg"></i> Approve
                        </button>
                        <button
                          class="btn btn-sm btn-danger"
                          @click="rejectUser(user)"
                        >
                          <i class="bi bi-x-lg"></i> Reject
                        </button>
                      </div>
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
