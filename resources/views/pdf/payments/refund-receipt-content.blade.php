<div style="font-family: DejaVu Sans, sans-serif; color: #0f172a;">
    <div style="border: 1px solid #dbe3f0; border-radius: 18px; padding: 28px;">
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; vertical-align: top;">
                <div style="font-size: 11px; letter-spacing: 0.32em; text-transform: uppercase; color: #64748b; font-weight: 700;">{{ $document['document_title'] }}</div>
                <h1 style="margin: 12px 0 0; font-size: 30px; line-height: 1.1;">{{ $document['event_name'] }}</h1>
                <div style="margin-top: 8px; font-size: 14px; color: #475569;">{{ $document['event_organizer'] }}</div>
            </div>
            <div style="display: table-cell; width: 220px; vertical-align: top; text-align: right;">
                <div style="font-size: 12px; letter-spacing: 0.24em; text-transform: uppercase; color: #64748b; font-weight: 700;">Documento</div>
                <div style="margin-top: 10px; font-size: 22px; font-weight: 800;">{{ $document['document_number'] }}</div>
                <div style="margin-top: 8px; font-size: 13px; color: #475569;">Emitido {{ $document['issued_at']->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <div style="margin-top: 24px; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 50%; padding-right: 14px; vertical-align: top;">
                    <div style="font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: #64748b; font-weight: 700;">Participante</div>
                    <div style="margin-top: 10px; font-size: 22px; font-weight: 700;">{{ $document['participant_name'] }}</div>
                    <div style="margin-top: 8px; font-size: 14px; color: #334155;">Identidad: {{ $document['participant_identity'] }}</div>
                    <div style="margin-top: 4px; font-size: 14px; color: #334155;">Correo: {{ $document['participant_email'] }}</div>
                </div>
                <div style="display: table-cell; width: 50%; padding-left: 14px; vertical-align: top;">
                    <div style="font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: #64748b; font-weight: 700;">Autorización</div>
                    <div style="margin-top: 10px; font-size: 16px; font-weight: 700;">Creador del evento</div>
                    <div style="margin-top: 8px; font-size: 14px; color: #334155;">{{ $document['creator_name'] }}</div>
                    <div style="margin-top: 4px; font-size: 14px; color: #334155;">Procesado por {{ $document['issuer_name'] }}</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 24px; border-radius: 18px; background: #fff7ed; padding: 18px 20px;">
            <div style="font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: #9a3412; font-weight: 700;">Monto devuelto</div>
            <div style="margin-top: 12px; display: table; width: 100%;">
                <div style="display: table-cell; vertical-align: middle;">
                    <div style="font-size: 34px; font-weight: 900; color: #7c2d12;">{{ $document['payment_amount_formatted'] ?? number_format($document['payment_amount'], 2) }}</div>
                    <div style="margin-top: 6px; font-size: 14px; color: #7c2d12;">Referencia {{ $document['payment_reference'] }}</div>
                </div>
                <div style="display: table-cell; width: 230px; vertical-align: middle;">
                    <div style="font-size: 12px; color: #9a3412;">Método original</div>
                    <div style="margin-top: 4px; font-size: 16px; font-weight: 700; color: #7c2d12;">{{ $document['payment_method'] }}</div>
                </div>
            </div>
        </div>

        @if(!empty($document['payment_notes']))
            <div style="margin-top: 20px;">
                <div style="font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: #64748b; font-weight: 700;">Motivo de devolución</div>
                <div style="margin-top: 8px; font-size: 14px; color: #334155;">{{ $document['payment_notes'] }}</div>
            </div>
        @endif

        <div style="margin-top: 34px; display: table; width: 100%;">
            <div style="display: table-cell; width: 50%; vertical-align: top; padding-right: 16px;">
                <div style="border-top: 1px solid #94a3b8; padding-top: 10px; font-size: 13px; color: #475569;">
                    {{ $document['receiver_signature_label'] ?? 'Firma de quien recibe la devolución' }}
                </div>
            </div>
            <div style="display: table-cell; width: 50%; vertical-align: top; padding-left: 16px;">
                <div style="border-top: 1px solid #94a3b8; padding-top: 10px; font-size: 13px; color: #475569; text-align: right;">
                    Entregado por {{ $document['issuer_name'] }}
                </div>
            </div>
        </div>
    </div>
</div>
