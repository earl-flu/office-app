<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
  employees: {
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
    route("employees.index"),
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
</script>

<template>
  <Head title="Employees" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Employees</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">All</li>
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
                <h5 class="mb-0">All Employees</h5>
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
                  placeholder="Name or Employee ID"
                  v-model="search"
                  autocomplete="off"
                />
              </div>
            </form>
            <!-- End of search inputs -->

            <!-- Add employee -->
            <div class="d-flex align-items-start justify-content-between mb-4">
              <div></div>
              <Link
                class="btn btn-primary px-4"
                :href="route('employees.create')"
              >
                <i class="bi bi-plus-lg me-2"></i>Add New Employee
              </Link>
            </div>
            <!-- End of add employee -->

            <!-- Table -->
            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>Employee ID</th>
                    <th>Name</th>
                    <th>Gender</th>
                    <th>Division</th>
                    <th>Program</th>
                    <th>Facility</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="employee in employees.data" :key="employee.id">
                    <td>
                      <span class="fw-bold">{{ employee.employee_id }}</span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img
                          v-if="employee.profile_image"
                          :src="`/storage/${employee.profile_image}`"
                          :alt="employee.full_name"
                          class="rounded-circle"
                          style="width: 36px; height: 36px; object-fit: cover;"
                        />
                        <div
                          v-else
                          class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold"
                          style="width: 36px; height: 36px; font-size: 0.9rem;"
                        >
                          {{ employee.full_name?.charAt(0) || '?' }}
                        </div>
                        <p class="mb-0">{{ employee.full_name }}</p>
                      </div>
                    </td>
                    <td>{{ employee.gender || '-' }}</td>
                    <td>{{ employee.division || '-' }}</td>
                    <td>{{ employee.program?.name || '-' }}</td>
                    <td>{{ employee.facility?.name || '-' }}</td>
                    <td>
                      <div class="d-flex align-items-center gap-1">
                        <Link
                          :href="route('employees.show', employee)"
                          class="btn btn-sm btn-info"
                        >
                          <i class="bi bi-eye"></i>
                        </Link>
                        <div class="dropdown">
                          <button
                            class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-nocaret"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                          >
                            <i class="bi bi-three-dots"></i>
                          </button>
                          <ul class="dropdown-menu">
                            <Link :href="route('employees.edit', employee)">
                              <a class="dropdown-item" href="javascript:;"
                                ><i class="bi bi-pencil-square me-2"></i>Edit</a
                              >
                            </Link>
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <Pagination :links="employees.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
