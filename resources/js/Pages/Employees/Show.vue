<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link } from "@inertiajs/vue3";

const props = defineProps({
  employee: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <Head title="Employee Details" />

  <AuthenticatedLayout>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Employee</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
          </ol>
        </nav>
      </div>
    </div>
    <!--end breadcrumb-->

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
              <h5 class="mb-0">Employee Details</h5>
              <div class="d-flex gap-2">
                <Link
                  :href="route('employees.edit', employee)"
                  class="btn btn-primary"
                >
                  <i class="bi bi-pencil-square me-2"></i>Edit
                </Link>
                <Link
                  :href="route('employees.index')"
                  class="btn btn-secondary"
                >
                  Back to List
                </Link>
              </div>
            </div>

            <div class="row g-3">
              <div class="col-md-3">
                <label class="form-label fw-bold">Employee ID</label>
                <p class="mb-0">{{ employee.employee_id }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">First Name</label>
                <p class="mb-0">{{ employee.first_name }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Middle Name</label>
                <p class="mb-0">{{ employee.middle_name || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Last Name</label>
                <p class="mb-0">{{ employee.last_name }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Gender</label>
                <p class="mb-0">{{ employee.gender || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Suffix</label>
                <p class="mb-0">{{ employee.suffix || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Division</label>
                <p class="mb-0">{{ employee.division || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Program</label>
                <p class="mb-0">{{ employee.program?.name || "-" }}</p>
              </div>

              <div class="col-md-3">
                <label class="form-label fw-bold">Facility</label>
                <p class="mb-0">
                  {{ employee.facility?.name || "-" }}
                  <span v-if="employee.facility?.facility_type">
                    ({{ employee.facility.facility_type.name }})
                  </span>
                </p>
              </div>
            </div>

            <div
              v-if="
                employee.assigned_tasks && employee.assigned_tasks.length > 0
              "
              class="mt-5"
            >
              <h6 class="mb-3">Tasks Assigned by This Employee</h6>
              <div class="table-responsive">
                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>Task Description</th>
                      <th>Assigned To</th>
                      <th>Time Spent</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="task in employee.assigned_tasks" :key="task.id">
                      <td>{{ task.task_description }}</td>
                      <td>{{ task.assigned_to_user?.full_name || "-" }}</td>
                      <td>
                        {{
                          task.time_spent_minutes
                            ? task.time_spent_minutes + " mins"
                            : "-"
                        }}
                      </td>
                      <td>
                        <span v-if="task.completed_at" class="badge bg-success"
                          >Completed</span
                        >
                        <span v-else class="badge bg-warning">Pending</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
