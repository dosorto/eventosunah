<div style="font-family: DejaVu Sans, sans-serif; color: #0f172a;">
    <div style="border: 1px solid #dbe3f0; border-radius: 18px; padding: 28px;">
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; vertical-align: top;">
                <div style="font-size: 11px; letter-spacing: 0.32em; text-transform: uppercase; color: #64748b; font-weight: 700;">{{ $document['document_title'] }}</div>
                <h1 style="margin: 12px 0 0; font-size: 28px; line-height: 1.1;">{{ $document['fiscal_name'] }}</h1>
                <div style="margin-top: 8px; font-size: 14px; color: #334155;">RTN {{ $document['fiscal_rtn'] }}</div>
                @if(!empty($document['fiscal_address']))
                    <div style="margin-top: 4px; font-size: 13px; color: #475569;">{{ $document['fiscal_address'] }}</div>
                @endif
                @if(!empty($document['fiscal_phone']) || !empty($document['fiscal_email']))
                    <div style="margin-top: 4px; font-size: 13px; color: #475569;">
                        {{ $document['fiscal_phone'] ?: '' }}@if(!empty($document['fiscal_phone']) && !empty($document['fiscal_email'])) · @endif{{ $document['fiscal_email'] ?: '' }}
                    </div>
                @endif
            </div>
            <div style="display: table-cell; width: 260px; vertical-align: top; text-align: right;">
                <div style="font-size: 12px; letter-spacing: 0.24em; text-transform: uppercase; color: #64748b; font-weight: 700;">Factura</div>
                <div style="margin-top: 10px; font-size: 22px; font-weight: 800;">{{ $document['document_number'] }}</div>
                <div style="margin-top: 8px; font-size: 13px; color: #475569;">CAI {{ $document['fiscal_cai'] }}</div>
                <div style="margin-top: 4px; font-size: 13px; color: #475569;">Emitida {{ $document['issued_at']->format('d/m/Y H:i') }}</div>
                @if($document['expiry_date'])
                    <div style="margin-top: 4px; font-size: 13px; color: #475569;">Límite {{ $document['expiry_date']->format('d/m/Y') }}</div>
                @endif
            </div>
        </div>

        <div style="margin-top: 22px; border-top: 1px solid #e2e8f0; padding-top: 18px;">
            <div style="display: table; width: 100%;">
                <div style="display: table-cell; width: 48%; padding-right: 14px; vertical-align: top;">
                    <div style="font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: #64748b; font-weight: 700;">Facturar a</div>
                    <div style="margin-top: 10px; font-size: 20px; font-weight: 700;">{{ $document['participant_name'] }}</div>
                    <div style="margin-top: 8px; font-size: 14px; color: #334155;">Identidad / RTN: {{ $document['participant_identity'] }}</div>
                    <div style="margin-top: 4px; font-size: 14px; color: #334155;">Correo: {{ $document['participant_email'] }}</div>
                    <div style="margin-top: 4px; font-size: 14px; color: #334155;">Teléfono: {{ $document['participant_phone'] }}</div>
                </div>
                <div style="display: table-cell; width: 52%; padding-left: 14px; vertical-align: top;">
                    <div style="font-size: 11px; letter-spacing: 0.24em; text-transform: uppercase; color: #64748b; font-weight: 700;">Concepto</div>
                    <div style="margin-top: 10px; font-size: 18px; font-weight: 700;">Inscripción a {{ $document['event_name'] }}</div>
                    <div style="margin-top: 8px; font-size: 14px; color: #334155;">{{ $document['event_dates'] }}</div>
                    <div style="margin-top: 4px; font-size: 14px; color: #334155;">{{ $document['event_location'] }}</div>
                    <div style="margin-top: 4px; font-size: 14px; color: #334155;">Método: {{ $document['payment_method'] }}</div>
                </div>
            </div>
        </div>

        <div style="margin-top: 24px; border-radius: 18px; background: #f8fafc; padding: 18px 20px;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr>
                        <th style="text-align: left; font-size: 11px; letter-spacing: 0.22em; text-transform: uppercase; color: #64748b; padding-bottom: 8px;">Descripción</th>
                        <th style="text-align: right; font-size: 11px; letter-spacing: 0.22em; text-transform: uppercase; color: #64748b; padding-bottom: 8px;">Monto</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 10px 0; font-size: 15px; color: #0f172a;">Inscripción confirmada para {{ $document['event_name'] }}</td>
                        <td style="padding: 10px 0; text-align: right; font-size: 20px; font-weight: 800; color: #0f172a;">{{ $document['payment_amount_formatted'] ?? number_format($document['payment_amount'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
            <div style="margin-top: 10px; font-size: 13px; color: #475569;">Referencia del pago {{ $document['payment_reference'] }}</div>
        </div>

        @if(!empty($document['fiscal_legend']))
            <div style="margin-top: 18px; font-size: 12px; color: #475569;">
                {{ $document['fiscal_legend'] }}
            </div>
        @endif
    </div>
</div>
