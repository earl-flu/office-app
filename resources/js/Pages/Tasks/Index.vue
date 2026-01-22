<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
  tasks: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
  },
});

let search = ref(props.filters.search);
let userId = ref(props.filters.user_id);

const debouncedFetch = debounce((search, userId) => {
  router.get(
    route("tasks.index"),
    { search, user_id: userId },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
}, 300);

watch([search, userId], (values) => {
  const [search, userId] = values;
  debouncedFetch(search, userId);
});
</script>

<template>
  <Head title="Tasks" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Tasks</div>
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
                <h5 class="mb-0">All Tasks</h5>
              </div>
            </div>

            <!-- Search inputs -->
            <form class="row g-3 mb-5">
              <div class="col-md-6">
                <label for="search" class="form-label">Search</label>
                <input
                  type="text"
                  class="form-control"
                  id="search"
                  placeholder="Task description"
                  v-model="search"
                  autocomplete="off"
                />
              </div>
            </form>
            <!-- End of search inputs -->

            <!-- Add task -->
            <div class="d-flex align-items-start justify-content-between mb-4">
              <div></div>
              <Link class="btn btn-primary px-4" :href="route('tasks.create')">
                <i class="bi bi-plus-lg me-2"></i>Add New Task
              </Link>
            </div>
            <!-- End of add task -->

            <!-- Table -->
            <div class="table-responsive">
              <table class="table align-middle table-hover">
                <thead class="table-dark">
                  <tr>
                    <th>Task Description</th>
                    <th>Assigned By</th>
                    <th>Assigned To</th>
                    <th>Time Spent</th>
                    <th>Started At</th>
                    <th>Completed At</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="task in tasks.data" :key="task.id">
                    <td>
                      <p class="mb-0">{{ task.task_description }}</p>
                    </td>
                    <td>
                      {{ task.assigned_by_employee?.full_name || "-" }}
                    </td>
                    <td>
                      {{ task.assigned_to_user?.full_name || "-" }}
                    </td>
                    <td>
                      {{
                        task.time_spent_minutes
                          ? task.time_spent_minutes + " mins"
                          : "-"
                      }}
                    </td>
                    <td>
                      {{
                        task.started_at
                          ? new Date(task.started_at).toLocaleString()
                          : "-"
                      }}
                    </td>
                    <td>
                      {{
                        task.completed_at
                          ? new Date(task.completed_at).toLocaleString()
                          : "-"
                      }}
                    </td>
                    <td>
                      <span v-if="task.completed_at" class="badge bg-success"
                        >Completed</span
                      >
                      <span v-else class="badge bg-warning">Pending</span>
                    </td>
                    <td>
                      <div class="d-flex align-items-center gap-1">
                        <Link
                          :href="route('tasks.show', task)"
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
                            <Link :href="route('tasks.edit', task)">
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
              <Pagination :links="tasks.links" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
