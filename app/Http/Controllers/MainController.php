<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrackPriceRequest;
use App\Services\AnnouncementService;
use App\Services\CrawlerService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\RedirectResponse;

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
     * @return RedirectResponse
     * @throws GuzzleException
     */
    public function __invoke(TrackPriceRequest $request): RedirectResponse
    {
        $url = $request->get('announcement');
        $page = $this->crawlerService->fetchHtmlPage($url);
        $announcementId = $this->crawlerService->findAnnouncementId($page);
        $announcementPrice = $this->announcementService->getAnnouncementPrice($announcementId);

        if (!$announcementPrice) {
            return redirect()->back()->withErrors(['announcement' => 'В оголошенні не знайдена ціна'])->withInput();
        }

        $announcement = $this->announcementService->createAnnouncement($announcementId, $announcementPrice, $url);

        $status = $this->announcementService->associateAnnouncementWithUser($announcement->id);

        return $status
            ? redirect()->back()->with(['success' => __('Ви успішно підписались на відслідковування ціни')])
            : redirect()->back()->with(['error' => __('Ви вже слідкуєте за ціною в цьому оголошенні')]);
    }
}
