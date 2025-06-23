<p><strong>Regex Patterns</strong></p>
<div class="form-group">
    <label>Type</label>
    <select class="form-control" id="regex-type">
        <option value="">-- Select Type Attack --</option>
        <option value="sql_patterns">SQL Injection</option>
        <option value="xss_patterns">Cross Site Scripting</option>
        <option value="unknown_attack_patterns">Unknow Attack</option>
    </select>
</div>

<div class="form-group">
    <label>Add Your Regex</label>
    <div class="d-flex">
        <input type="text" class="form-control mr-2" id="custom-regex-key" placeholder="Name">
        <input type="text" class="form-control mr-2" id="custom-regex-value" placeholder="Value">
        <button type="button" class="btn btn-sm btn-outline-primary" id="add-custom-regex">Add</button>
    </div>
</div>

<ul class="list-group mt-2" id="custom-regex-list">
    <!-- Custom regex will be listed here -->
</ul>
