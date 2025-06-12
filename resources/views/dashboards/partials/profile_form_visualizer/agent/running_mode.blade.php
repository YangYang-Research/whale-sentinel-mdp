<p><strong>Agent Model</strong></p>
<div class="form-group">
    <label>Running Mode</label>
    <select class="form-control" id="running_mode">
        <option value="">-- Select Run Mode --</option>
        <option value="off">Off</option>
        <option value="lite">Lite</option>
        <option value="monitor">Monitor</option>
        <option value="protection">Protection</option>
    </select>
</div>

<div class="form-group">
    <label>Last Run Mode (Default: Lite)</label>
    <select class="form-control" id="last_run_mode">
        <option value="">-- Select Last Run Mode --</option>
        <option value="off">Off</option>
        <option value="lite" selected>Lite</option>
        <option value="monitor">Monitor</option>
        <option value="protection">Protection</option>
    </select>
</div>

<div class="form-group form-check">
    <input type="checkbox" class="form-check-input" id="lite_mode_data_is_synchronized" disabled>
    <label class="form-check-label" for="lite_mode_data_is_synchronized">Lite Mode Data Is Synchronized</label>
</div>

<div class="form-group">
    <label>Sync Status</label>
    <select class="form-control" id="lite_mode_data_synchronize_status">
        <option value="">-- Select Sync Status --</option>
        <option value="successed">Successed</option>
        <option value="inprogress">Inprogress</option>
        <option value="failure">Failure</option>
        <option value="none" selected>None</option>
    </select>
</div>