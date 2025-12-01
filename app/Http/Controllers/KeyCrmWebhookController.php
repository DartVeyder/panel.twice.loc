<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order; // Ваш модель замовлення в Orchid

class KeyCrmWebhookController extends Controller
{
    /**
     * Обробка вхідного вебхука від KeyCRM
     */
    public function handle(Request $request)
    {
        // 1. Логування вхідних даних (корисно для налагодження)
        Log::channel('daily')->info('KeyCRM Webhook:', $request->all());

        // 2. Отримання події та даних
        $event = $request->input('event'); // наприклад: 'order.created' або 'order.updated'
        $data = $request->input('context'); // Основні дані сутності

        if (!$data) {
            return response()->json(['status' => 'error', 'message' => 'No context data'], 400);
        }

        try {
            switch ($event) {
                case 'order.created':
                case 'order.updated':
                    $this->syncOrder($data);
                    break;
                
                // Додайте інші кейси за потреби (наприклад, клієнти)
                default:
                    Log::info("Event {$event} skipped.");
            }

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error('KeyCRM Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Логіка синхронізації замовлення
     */
    protected function syncOrder(array $crmOrderData)
    {
        // Приклад: оновлюємо або створюємо замовлення в базі Laravel
        // Припускаємо, що у вас є поле 'keycrm_id' у таблиці orders
        
        $order = Order::updateOrCreate(
            ['keycrm_id' => $crmOrderData['id']], // Пошук по ID з CRM
            [
                'grand_total'   => $crmOrderData['grand_total'] ?? 0,
                'status'        => $crmOrderData['status_name'] ?? 'new',
                'client_name'   => $crmOrderData['client']['full_name'] ?? 'Guest',
                'phone'         => $crmOrderData['client']['phone'] ?? null,
                // Додайте інші поля, які є у вашій моделі Orchid
                'raw_data'      => $crmOrderData, // Корисно зберігати повний JSON
            ]
        );
        
        // Тут можна додати логіку сповіщень Orchid (Toast/Alerts), якщо потрібно
    }
}