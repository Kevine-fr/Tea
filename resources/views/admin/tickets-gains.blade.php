@extends('layouts.admin')
@section('title','Tickets & Gains — Administration')

@section('page_styles')
.page-title{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;text-align:center;margin-bottom:1.5rem}
.table-card{background:var(--white);border-radius:var(--r);box-shadow:var(--sh-sm);overflow:hidden}
.maj-btn{background:var(--orange);color:var(--white);border:none;padding:.38rem .95rem;border-radius:20px;font-family:'Jost',sans-serif;font-weight:700;font-size:.8rem;cursor:pointer;transition:var(--t);white-space:nowrap}
.maj-btn:hover{background:var(--orange-h);transform:translateY(-1px);box-shadow:0 4px 12px rgba(217,79,30,.35)}
.modal-inner select.input-field{appearance:none;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 20 20' fill='none' stroke='%23666' stroke-width='1.5' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M5 7.5l5 5 5-5'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right .8rem center;background-size:16px}
@endsection

@section('content')

<div style="max-width:1000px">
    <h1 class="page-title fade-up">Suivi de mes gains</h1>

    <div class="table-card fade-up s1">
        <div style="overflow-x:auto">
            <table class="admin-table" id="gainsTable">
                <thead>
                    <tr>
                        <th>N° Ticket</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Mail</th>
                        <th>Lot</th>
                        <th>Date limite</th>
                        <th>Statut</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ticketsGains ?? [] as $item)
                    <tr class="searchable-row">
                        <td style="font-family:monospace;font-weight:700;color:var(--green)">
                            {{ $item->ticketCode->code ?? '—' }}
                        </td>
                        <td>{{ $item->user->last_name ?? '—' }}</td>
                        <td>{{ $item->user->first_name ?? '—' }}</td>
                        <td style="font-size:.8rem;color:var(--txt-m)">{{ $item->user->email ?? '—' }}</td>
                        <td style="font-weight:600">{{ $item->prize->name ?? '—' }}</td>
                        <td style="font-size:.82rem">
                            {{ $item->redemption?->deadline ? \Carbon\Carbon::parse($item->redemption->deadline)->format('d/m/Y') : '—' }}
                        </td>
                        <td>
                            @php $st = $item->redemption?->status ?? 'none'; @endphp
                            @if($st==='completed') <span style="font-weight:600">Remis</span>
                            @elseif($st==='approved') <span style="font-weight:600">En préparation</span>
                            @elseif($st==='pending') <span style="font-weight:600">En préparation</span>
                            @elseif($st==='store') <span style="font-weight:600">Disponible en boutique</span>
                            @else <span style="color:var(--txt-l)">À traiter</span>
                            @endif
                        </td>
                        <td>
                            <button class="maj-btn" type="button"
                                onclick="openUpdateModal(
                                    '{{ $item->id }}',
                                    '{{ $item->prize->name ?? '' }}',
                                    '{{ $item->redemption?->status ?? 'pending' }}',
                                    '{{ $item->redemption?->deadline ?? '' }}'
                                )">MAJ</button>
                        </td>
                    </tr>
                    @empty
                    {{-- Demo rows for design preview --}}
                    @foreach([['123456','Porte','Katia','P.K@gmail.com','Gain 1','06/07/2026','Remis'],['789012','Declair','Samy','D.S@gmail.com','Gain 2','13/08/2026','En préparation'],['172839','Hernandez','Julie','H.J@gmail.com','Gain 3','21/09/2026','Disponible en boutique']] as $demo)
                    <tr class="searchable-row">
                        <td style="font-family:monospace;font-weight:700;color:var(--green)">{{ $demo[0] }}</td>
                        <td>{{ $demo[1] }}</td>
                        <td>{{ $demo[2] }}</td>
                        <td style="font-size:.8rem;color:var(--txt-m)">{{ $demo[3] }}</td>
                        <td style="font-weight:600">{{ $demo[4] }}</td>
                        <td style="font-size:.82rem">{{ $demo[5] }}</td>
                        <td style="font-weight:600">{{ $demo[6] }}</td>
                        <td><button class="maj-btn" type="button" onclick="openUpdateModal('demo','{{ $demo[4] }}','pending','')">MAJ</button></td>
                    </tr>
                    @endforeach
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(isset($ticketsGains) && method_exists($ticketsGains,'hasPages') && $ticketsGains->hasPages())
        <div style="padding:1rem;text-align:center">{{ $ticketsGains->links() }}</div>
        @endif
    </div>
</div>

{{-- Update Modal - matching Image 5 exactly --}}
<div class="modal-overlay" id="updateModal">
    <div class="modal modal-inner">
        <h3 class="modal-title">Mettre à jour le statut<br>du lot</h3>
        <form method="POST" id="updateForm" action="">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label class="form-label">Lot</label>
                <input type="text" name="prize_name" id="modalPrize" class="input-field" readonly>
            </div>
            <div class="form-group">
                <label class="form-label">Statut</label>
                <select name="status" id="modalStatus" class="input-field">
                    <option value="pending">En attente</option>
                    <option value="approved">En préparation</option>
                    <option value="completed">Remis</option>
                    <option value="store">Disponible en boutique</option>
                    <option value="rejected">Refusé</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Date limite</label>
                <input type="date" name="deadline" id="modalDeadline" class="input-field">
            </div>
            <div style="text-align:center;margin-top:1.5rem;display:flex;gap:1rem;justify-content:center">
                <button type="button" class="btn btn-sm" style="background:var(--cream-d);color:var(--txt)" onclick="closeModal('updateModal')">
                    Annuler
                </button>
                <button type="submit" class="btn btn-orange">Mettre à jour</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openUpdateModal(id, prize, status, deadline) {
    document.getElementById('modalPrize').value = prize;
    document.getElementById('modalStatus').value = status;
    if (deadline) {
        // Convert dd/mm/yyyy to yyyy-mm-dd if needed
        if (deadline.includes('/')) {
            const parts = deadline.split('/');
            deadline = `${parts[2]}-${parts[1]}-${parts[0]}`;
        }
        document.getElementById('modalDeadline').value = deadline;
    }
    const form = document.getElementById('updateForm');
    form.action = id !== 'demo' ? `/admin/tickets-gains/${id}` : '#';
    openModal('updateModal');
}
</script>
@endpush
@endsection