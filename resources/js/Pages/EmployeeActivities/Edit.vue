<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, useForm, Link } from "@inertiajs/vue3";
import { useToast } from "vue-toastification";

const props = defineProps({
  activity: {
    type: Object,
    required: true,
  },
  employees: {
    type: Array,
    default: () => [],
  },
  activityTypes: {
    type: Array,
    default: () => [],
  },
  mfos: {
    type: Array,
    default: () => [],
  },
  activityStatuses: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  assigned_by_id: props.activity.assigned_by_id || null,
  activity_type_id: props.activity.activity_type_id || "",
  description: props.activity.description || "",
  activity_status_id: props.activity.activity_status_id || "pending",
  remarks: props.activity.remarks || "",
  time_spent_minutes: props.activity.time_spent_minutes || null,
  activity_date:
    props.activity.activity_date || new Date().toISOString().slice(0, 10),
  mfo_id: props.activity.mfo_id,
});

const submit = () => {
  form.put(route("employee-activities.update", props.activity.id), {
    onSuccess: () => {
      const toast = useToast();
      toast.success("Employee Activity Updated Successfully", {
        timeout: 3000,
      });
    },
  });
};
</script>

<template>
  <Head title="Edit Employee Activity" />

  <AuthenticatedLayout>
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
      <div class="breadcrumb-title pe-3">Employee Activity</div>
      <div class="ps-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 p-0">
            <li class="breadcrumb-item">
              <a href="javascript:;"><i class="bx bx-home-alt"></i></a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Edit</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row">
      <div class="col-12 col-xl-8">
        <div class="card">
          <div class="card-body p-4">
            <h5 class="mb-4">Edit Employee Activity</h5>
            <form class="row g-3" @submit.prevent="submit">
              <div class="col-md-6">
                <label for="assigned_by_id" class="form-label"
                  >Assigned By *</label
                >
                <select
                  class="form-select"
                  v-model="form.assigned_by_id"
                  id="assigned_by_id"
                  required
                >
                  <option value="">Select Employee</option>
                  <option
                    v-for="employee in employees"
                    :key="employee.id"
                    :value="employee.id"
                  >
                    {{ employee.full_name }}
                    <span v-if="employee.employee_id"
                      >({{ employee.employee_id }})</span
                    >
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.assigned_by_id }}
                </div>
              </div>

              <div class="col-md-6">
                <label for="activity_type_id" class="form-label"
                  >Activity Type *</label
                >
                <select
                  class="form-select"
                  v-model="form.activity_type_id"
                  id="activity_type_id"
                  required
                >
                  <option value="">Select Activity Type</option>
                  <option
                    v-for="activityType in activityTypes"
                    :key="activityType.id"
                    :value="activityType.id"
                  >
                    {{ activityType.name }}
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.activity_type_id }}
                </div>
              </div>

              <div class="col-md-12">
                <label for="description" class="form-label"
                  >Description *</label
                >
                <textarea
                  class="form-control"
                  v-model="form.description"
                  id="description"
                  rows="4"
                  required
                ></textarea>
                <div class="invalid-feedback d-block">
                  {{ form.errors.description }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="activity_status_id" class="form-label"
                  >Status *</label
                >
                <select
                  class="form-select"
                  v-model="form.activity_status_id"
                  id="activity_status_id"
                  required
                >
                  <option
                    v-for="status in activityStatuses"
                    :key="status.id"
                    :value="status.id"
                  >
                    {{ status.description }}
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.activity_status_id }}
                </div>
              </div>
              <div class="col-md-3">
                <label for="mfo_id" class="form-label">MFO *</label>
                <select
                  class="form-select"
                  v-model="form.mfo_id"
                  id="mfo_id"
                  required
                >
                  <option value="">Select MFO</option>
                  <option v-for="mfo in mfos" :key="mfo.id" :value="mfo.id">
                    {{ mfo.code }}: {{ mfo.description }}
                  </option>
                </select>
                <div class="invalid-feedback d-block">
                  {{ form.errors.mfo_id }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="activity_date" class="form-label"
                  >Activity Date *</label
                >
                <input
                  type="date"
                  class="form-control"
                  v-model="form.activity_date"
                  id="activity_date"
                  required
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.activity_date }}
                </div>
              </div>

              <div class="col-md-3">
                <label for="time_spent_minutes" class="form-label"
                  >Time Spent (minutes)</label
                >
                <input
                  type="number"
                  class="form-control"
                  v-model="form.time_spent_minutes"
                  id="time_spent_minutes"
                  min="0"
                />
                <div class="invalid-feedback d-block">
                  {{ form.errors.time_spent_minutes }}
                </div>
              </div>

              <div class="col-md-12">
                <label for="remarks" class="form-label">Remarks</label>
                <textarea
                  class="form-control"
                  v-model="form.remarks"
                  id="remarks"
                  rows="3"
                ></textarea>
                <div class="invalid-feedback d-block">
                  {{ form.errors.remarks }}
                </div>
              </div>

              <div class="col-md-12 mt-4">
                <div class="d-md-flex d-grid align-items-center gap-3">
                  <button
                    class="btn btn-grd btn-grd-primary px-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                  >
                    Save
                  </button>
                  <Link
                    :href="route('employee-activities.index')"
                    class="btn btn-secondary px-4"
                    >Cancel</Link
                  >
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

