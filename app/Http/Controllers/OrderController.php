<?php

namespace App\Http\Controllers;

use App\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    private OrderRepositoryInterface $orderRepository;

    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index(): JsonResponse // it calls the getAllOrders() method defined in the orderRepository
    {
        return response()->json([
            'data' => $this->orderRepository->getAllOrders()
        ]);
    }

    public function store(Request $request): JsonResponse // method calls the createOrder() method from the orderRepository to create a new order
    {
        $orderDetails = $request->only([
            'client',
            'details'
        ]);

        return response()->json(
            [
                'data' => $this->orderRepository->createOrder($orderDetails)
            ],
            Response::HTTP_CREATED
        );
    }

    public function show(Request $request): JsonResponse //it retrieves the unique order Id from the route and passes it to the getOrderById() as a paramete
    {
        $orderId = $request->route('id');

        return response()->json([
            'data' => $this->orderRepository->getOrderById($orderId)
        ]);
    }

    public function update(Request $request): JsonResponse //to update the details of an already created order
    {
        $orderId = $request->route('id');
        $orderDetails = $request->only([
            'client',
            'details'
        ]);

        return response()->json([
            'data' => $this->orderRepository->updateOrder($orderId, $orderDetails)
        ]);
    }

    public function delete(Request $request): Response // etrieves the unique id of a particular order from the route and calls the deleteOrder() method from the repository to delete it
    {
        $orderId = $request->route('id');
        $this->orderRepository->deleteOrder($orderId);

        return response()->noContent();
    }
}
