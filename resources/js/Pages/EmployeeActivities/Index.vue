<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { debounce } from "lodash";
import Pagination from "@/Components/Pagination.vue";

const props = defineProps({
  activities: {
    type: Object,
    required: true,
  },
  filters: {
    type: Object,
  },
  employees: {
    type: Array,
    default: () => [],
  },
  activityTypes: {
    type: Array,
    default: () => [],
  },
});

let search = ref(props.filters?.search || "");
let status = ref(props.filters?.status || "");
let employeeId = ref(props.filters?.employee_id || "");
let assignedById = ref(props.filters?.assigned_by_id || "");
let dateFrom = ref(props.filters?.date_from || "");
let dateTo = ref(props.filters?.date_to || "");
let perPage = ref(props.filters?.per_page || 10);

const debouncedFetch = debounce(() => {
  router.get(
    route("employee-activities.index"),
    {
      search: search.value,
      status: status.value,
      employee_id: employeeId.value,
      assigned_by_id: assignedById.value,
      date_from: dateFrom.value,
      date_to: dateTo.value,
      per_page: perPage.value,
    },
    {
      preserveState: true,
      preserveScroll: true,
    }
  );
}, 300);

watch(
  [search, status, employeeId, assignedById, dateFrom, dateTo, perPage],
  debouncedFetch
);
</script>

<template>
  <Head title="Employee Activities" />

  <AuthenticatedLayout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-4">
      <div class="breadcrumb-title pe-3">Employee Activities</div>
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

    <div class="row">
      <div class="card w-100 rounded-4">
        <div class="card-body">
          <div class="d-flex align-items-start justify-content-between mb-4">
            <h5 class="mb-0">All Employee Activities</h5>
            <Link
              class="btn btn-primary px-4"
              :href="route('employee-activities.create')"
            >
              <i class="bi bi-plus-lg me-2"></i>Add Activity
            </Link>
          </div>

          <form class="row g-3 mb-4">
            <div class="col-md-3">
              <label for="search" class="form-label">Search</label>
              <input
                type="text"
                id="search"
                class="form-control"
                v-model="search"
                placeholder="Description, name or employee id"
              />
            </div>
            <div class="col-md-2">
              <label for="status" class="form-label">Status</label>
              <select id="status" class="form-select" v-model="status">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="finished">Finished</option>
                <option value="cancelled">Cancelled</option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="employee" class="form-label">Employee</label>
              <select id="employee" class="form-select" v-model="employeeId">
                <option value="">All</option>
                <option v-for="e in employees" :key="e.id" :value="e.id">
                  {{ e.full_name }} ({{ e.employee_id }})
                </option>
              </select>
            </div>
            <div class="col-md-2">
              <label for="assigned_by" class="form-label">Assigned By</label>
              <select
                id="assigned_by"
                class="form-select"
                v-model="assignedById"
              >
                <option value="">All</option>
                <option v-for="e in employees" :key="`a-${e.id}`" :value="e.id">
                  {{ e.full_name }} ({{ e.employee_id }})
                </option>
              </select>
            </div>
            <div class="col-md-1">
              <label for="per_page" class="form-label">Per Page</label>
              <select
                id="per_page"
                class="form-select"
                v-model.number="perPage"
              >
                <option :value="10">10</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
              </select>
            </div>
            <div class="col-md-2 d-flex gap-2 align-items-end">
              <div class="w-50">
                <label class="form-label">From</label>
                <input type="date" class="form-control" v-model="dateFrom" />
              </div>
              <div class="w-50">
                <label class="form-label">To</label>
                <input type="date" class="form-control" v-model="dateTo" />
              </div>
            </div>
          </form>

          <div class="table-responsive">
            <table class="table align-middle table-hover">
              <thead class="table-dark">
                <tr>
                  <th>Date</th>
                  <th>Employee</th>
                  <th>Assigned By</th>
                  <th>Type</th>
                  <th>Description</th>
                  <th>Time (min)</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="activity in activities.data" :key="activity.id">
                  <td>{{ activity.activity_date }}</td>
                  <td>{{ activity.employee?.full_name || "-" }}</td>
                  <td>{{ activity.assigned_by?.full_name || "-" }}</td>
                  <td>{{ activity.activity_type?.name || "-" }}</td>
                  <td>
                    {{
                      activity.short_description ||
                      activity.description.slice(0, 60) +
                        (activity.description.length > 60 ? "..." : "")
                    }}
                  </td>
                  <td>{{ activity.time_spent_minutes ?? "-" }}</td>
                  <td>
                    <span
                      class="badge"
                      :class="{
                        'bg-warning text-dark': activity.status === 'pending',
                        'bg-info text-dark': activity.status === 'in_progress',
                        'bg-success': activity.status === 'finished',
                        'bg-secondary': activity.status === 'cancelled',
                      }"
                    >
                      {{ activity.status.replace("_", " ") }}
                    </span>
                  </td>
                  <td>
                    <div class="d-flex gap-1">
                      <Link
                        :href="route('employee-activities.show', activity)"
                        class="btn btn-sm btn-info"
                        ><i class="bi bi-eye"></i
                      ></Link>
                      <Link
                        :href="route('employee-activities.edit', activity)"
                        class="btn btn-sm btn-secondary"
                        ><i class="bi bi-pencil-square"></i
                      ></Link>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            <Pagination :links="activities.links" />
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

