<?php

namespace App\Console\Commands;

use App\Notifications\TrackedPriceEmailNotification;
use App\Services\AnnouncementService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;

class NotifySubscribers extends Command
{
    /**
     * The name and signature of the console command.
     * @var string
     */
    protected $signature = 'app:notify-subscribers';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Send email notification to users who track ad price';

    private AnnouncementService $announcementService;

    /**
     * @param AnnouncementService $announcementService
     */
    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $announcements = $this->announcementService->getAnnouncements();

        foreach ($announcements as $announcement) {
            if (!empty($announcement->subscribers)) {
                $currentAnnoucentPrice = $this->announcementService->getAnnouncementPrice($announcement->announcement_id);
                $notification = new TrackedPriceEmailNotification($announcement);

                if ((float) $currentAnnoucentPrice !== $announcement->price) {
                    foreach ($announcement->subscribers as $subscriber) {
                        $subscriber->notify($notification);
                    }
                }
            }
        }
    }
}
