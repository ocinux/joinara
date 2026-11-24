{{-- Shared form body for create/edit (subset of Event fields; token & owner never editable). --}}
<label class="label">Nama event *</label>
<input type="text" name="name" required maxlength="255" value="{{ old('name', $event->name) }}" />
<label class="label">Nama pasangan 1</label>
<input type="text" name="partner_1" maxlength="255" value="{{ old('partner_1', $event->partner_1) }}" />
<label class="label">Nama pasangan 2</label>
<input type="text" name="partner_2" maxlength="255" value="{{ old('partner_2', $event->partner_2) }}" />
<label class="label">Tanggal &amp; waktu acara</label>
<input type="datetime-local" name="starts_at" required
       value="{{ old('starts_at', $event->exists ? $event->starts_at->format('Y-m-d\TH:i') : '') }}" />
<label class="label">Lokasi</label>
<input type="text" name="location" maxlength="255" value="{{ old('location', $event->location) }}" />
@error('name')<p class="error">{{ $message }}</p>@enderror
@error('starts_at')<p class="error">{{ $message }}</p>@enderror
