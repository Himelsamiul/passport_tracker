{{-- ============================================================
     Permission matrix partial — role create/edit form er bhitor use hoy.
     Prottek module er jonno View / Create / Edit / Delete checkbox row.
     ============================================================ --}}
<div class="card">
  <div class="card-header"><i class="fas fa-key me-2" style="color:var(--accent-2)"></i>Permissions</div>
  <div class="card-body">
    <p class="text-muted" style="font-size:.8rem">
      Tick exactly what this role may do in each module. Dashboard &amp; Reports are view-only.
    </p>

    <div class="table-responsive">
      <table class="table align-middle perm-matrix">
        <thead>
          <tr>
            <th>Module</th>
            <th class="text-center" style="width:110px">View</th>
            <th class="text-center" style="width:110px">Create</th>
            <th class="text-center" style="width:110px">Edit</th>
            <th class="text-center" style="width:110px">Delete</th>
        </thead>
        <tbody>
          @foreach (\App\Support\Access::MODULES as $module => $actions)
            <tr>
              <td class="fw-semibold">{{ \App\Support\Access::MODULE_LABELS[$module] ?? ucfirst($module) }}</td>
              @foreach (['view', 'create', 'edit', 'delete'] as $action)
                <td class="text-center">
                  @if (in_array($action, $actions))
                    <input class="form-check-input perm-check" type="checkbox"
                           name="permissions[]" value="{{ $module }}.{{ $action }}"
                           @checked(in_array($module . '.' . $action, old('permissions', $role->permissions ?? [])))
                           data-module="{{ $module }}">
                  @else
                    <span class="text-muted">—</span>
                  @endif
                </td>
              @endforeach
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="d-flex gap-2 mt-2">
      <button type="button" class="btn btn-outline-secondary btn-sm" id="permAll">Select all</button>
      <button type="button" class="btn btn-outline-secondary btn-sm" id="permNone">Select none</button>
      <button type="button" class="btn btn-outline-secondary btn-sm" id="permViewOnly">View only</button>
    </div>
  </div>
</div>
