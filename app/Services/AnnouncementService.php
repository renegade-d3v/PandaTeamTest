<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\TrackedAd;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AnnouncementService
{
    public const OFFER_API_PREFIX = 'offers';

    private Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param int $announcementId
     * @return int|null
     * @throws GuzzleException
     */
    public function getAnnouncementPrice(int $announcementId): ?int
    {
        $url = $this->buildAnnouncementApiUrl($announcementId);

        $response = $this->client->get($url);
        $content = json_decode($response->getBody()->getContents(), true);

        return $this->findPriceInResponseContent($content);
    }

    /**
     * @param int $announcementId
     * @param float $announcementPrice
     * @param string $url
     * @return Model
     */
    public function createAnnouncement(int $announcementId, float $announcementPrice, string $url): Model
    {
        return Announcement::updateOrCreate(['announcement_id' => $announcementId], [
            'price' => $announcementPrice,
            'url' => $url
        ]);
    }

    /**
     * @param int $announcementId
     * @return bool
     */
    public function associateAnnouncementWithUser(int $announcementId): bool
    {
        $conditions = [
            'announcement_id' => $announcementId,
            'user_id' => Auth::id()
        ];

        $item = TrackedAd::firstOrNew($conditions);

        if (!$item->exists) {
            $item->fill($conditions);
            $item->save();

            return true;
        }

        return false;
    }

    /**
     * @return Collection
     */
    public function getAnnouncements(): Collection
    {
        return Announcement::with('subscribers')->get();
    }

    /**
     * @param int $announcementId
     * @return string
     */
    private function buildAnnouncementApiUrl(int $announcementId): string
    {
        return sprintf('%s/%s/%s', config('olx.base_api_path'), self::OFFER_API_PREFIX, $announcementId);
    }

    /**
     * @param mixed $content
     * @return int|null
     */
    private function findPriceInResponseContent(mixed $content): ?int
    {
        $keyIndex = array_search('price', array_column($content['data']['params'], 'key'));

        return $keyIndex !== false ? (int) $content['data']['params'][$keyIndex]['value']['value'] : null;
    }
}
