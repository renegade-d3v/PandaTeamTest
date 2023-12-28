<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TrackPriceRequest;
use App\Services\AnnouncementService;
use App\Services\CrawlerService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;

class MainController extends Controller
{
    private CrawlerService $crawlerService;

    private AnnouncementService $announcementService;

    /**
     * @param CrawlerService $crawlerService
     * @param AnnouncementService $announcementService
     */
    public function __construct(CrawlerService $crawlerService, AnnouncementService $announcementService)
    {
        $this->crawlerService = $crawlerService;
        $this->announcementService = $announcementService;
    }

    /**
     * @param TrackPriceRequest $request
     * @return JsonResponse
     * @throws GuzzleException
     */
    public function __invoke(TrackPriceRequest $request): JsonResponse
    {
        $url = $request->get('announcement');
        $page = $this->crawlerService->fetchHtmlPage($url);
        $announcementId = $this->crawlerService->findAnnouncementId($page);
        $announcementPrice = $this->announcementService->getAnnouncementPrice($announcementId);

        if (!$announcementPrice) {
            return response()->json(['announcement' => 'В оголошенні не знайдена ціна']);
        }

        $announcement = $this->announcementService->createAnnouncement($announcementId, $announcementPrice, $url);

        $status = $this->announcementService->associateAnnouncementWithUser($announcement->id);

        $msg = $status
            ? __('Ви успішно підписались на відслідковування ціни')
            : __('Ви вже слідкуєте за ціною в цьому оголошенні');

        return response()->json(['message' => $msg]);
    }
}
